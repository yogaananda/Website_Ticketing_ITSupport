<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConsumableController extends Controller
{
    // 1. MENAMPILKAN HALAMAN
    public function index(Request $request)
    {
        $query = Consumable::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        $consumables = $query->latest()->paginate(10);

        // PENTING 1: Di sini panggil NAMA FILE BLADE Anda
        return view('admin_stock_logistik', compact('consumables'));
    }

    // 2. SIMPAN DATA
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

        // PENTING 2: Di sini panggil NAMA ROUTE (Bukan nama file)
        // Nama route ini berasal dari web.php (admin. + consumables. + index)
        return redirect()->route('admin.consumables.index')
                         ->with('success', 'Stok berhasil ditambahkan!');
    }

    // 3. UPDATE DATA
    public function update(Request $request, $id)
    {
        $consumable = Consumable::findOrFail($id);
        
        // ... (Validasi sama seperti store) ...
        $request->validate([
            'name'      => 'required|string|max:255',
             // ... dst ...
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

        // PENTING 3: Redirect ke route admin.consumables.index
        return redirect()->route('admin.consumables.index')
                         ->with('success', 'Data stok berhasil diperbarui!');
    }

    // 4. HAPUS DATA
    public function destroy($id)
    {
        $consumable = Consumable::findOrFail($id);

        if ($consumable->image_path && Storage::disk('public')->exists($consumable->image_path)) {
            Storage::disk('public')->delete($consumable->image_path);
        }

        $consumable->delete();

        // PENTING 4: Redirect ke route admin.consumables.index
        return redirect()->route('admin.consumables.index')
                         ->with('success', 'Data stok berhasil dihapus!');
    }
}