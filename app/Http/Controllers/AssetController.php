<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with('user'); 

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
        }

        $assets = $query->latest()->paginate(10);
        
        $assets->getCollection()->transform(function($asset) {
            $asset->statusColor = match($asset->status) {
                'ready' => 'bg-green-100 text-green-800',
                'in_use' => 'bg-blue-100 text-blue-800',
                'lost' => 'bg-red-100 text-red-800',
                default => 'bg-gray-100 text-gray-800'
            };
            return $asset;
        });

        $users = User::all(); 

        return view('admin_assets', compact('assets', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'          => 'required|unique:assets,code',
            'name'          => 'required|string',
            'condition'     => 'required|in:good,maintenance,broken',
            'status'        => 'required|in:ready,in_use,lost',
            'user_id'       => 'nullable|exists:users,id',
            'image'         => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('assets', 'public');
        }

        Asset::create($data);

        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil didaftarkan!');
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $request->validate([
            'code'      => 'required|unique:assets,code,'.$id,
            'name'      => 'required|string',
            'image'     => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($asset->image_path) {
                Storage::disk('public')->delete($asset->image_path);
            }
            $data['image_path'] = $request->file('image')->store('assets', 'public');
        }

        $asset->update($data);

        return redirect()->route('admin.assets.index')->with('success', 'Data aset berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);
        if ($asset->image_path) {
            Storage::disk('public')->delete($asset->image_path);
        }
        $asset->delete();

        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil dihapus!');
    }
}