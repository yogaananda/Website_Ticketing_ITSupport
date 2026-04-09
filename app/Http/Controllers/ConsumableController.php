<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConsumableController extends Controller
{
    public function index(Request $request)
    {
        $query = Consumable::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }
        $consumables = $query->latest()->paginate(10);

        $consumables->getCollection()->transform(function($item) {
            if ($item->stock == 0) {
                $item->statusColor = 'bg-red-100 text-red-800 border-red-200';
                $item->statusLabel = 'Habis';
            } elseif ($item->stock <= $item->min_stock) {
                $item->statusColor = 'bg-amber-100 text-amber-800 border-amber-200';
                $item->statusLabel = 'Menipis';
            } else {
                $item->statusColor = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                $item->statusLabel = 'Aman';
            }
            return $item;
        });

        return view('admin_stock_logistik', compact('consumables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'category'  => 'required|string',
            'stock'     => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit'      => 'required|string|max:50',
            'location'  => 'nullable|string|max:255',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('consumables', 'public');
            $data['image_path'] = $path;
        }
        Consumable::create($data);
        return redirect()->route('admin.consumables.index')
                         ->with('success', 'Stok berhasil ditambahkan!');
    }
    public function update(Request $request, $id)
    {
        $consumable = Consumable::findOrFail($id);
        
        $request->validate([
            'name'      => 'required|string|max:255',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($consumable->image_path && Storage::disk('public')->exists($consumable->image_path)) {
                Storage::disk('public')->delete($consumable->image_path);
            }
            $path = $request->file('image')->store('consumables', 'public');
            $data['image_path'] = $path;
        }

        $consumable->update($data);
        return redirect()->route('admin.consumables.index')
                         ->with('success', 'Data stok berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $consumable = Consumable::findOrFail($id);

        if ($consumable->image_path && Storage::disk('public')->exists($consumable->image_path)) {
            Storage::disk('public')->delete($consumable->image_path);
        }

        $consumable->delete();
        return redirect()->route('admin.consumables.index')
                         ->with('success', 'Data stok berhasil dihapus!');
    }
}