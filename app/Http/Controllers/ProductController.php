<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // 🔍 Fitur pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%');
            });
        }

        // 🔽🔼 Fitur sorting kolom
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');

        $allowedSorts = ['id', 'product_name', 'unit', 'type', 'qty', 'producer'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $query->orderBy($sortBy, $sortOrder);

        $data = $query->paginate(5)->appends([
            'search' => $request->search,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ]);

        return view('master-data.product-master.index-product', compact('data', 'sortBy', 'sortOrder'));
    }

    public function create(): View
    {
        return view("Master-Data.Product-Master.create-product");
    }

    /**
     * ✅ Menyimpan data baru dengan notifikasi pop-up
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'product_name' => 'required|string|max:255',
                'unit' => 'required|string|max:50',
                'type' => 'required|string|max:50',
                'information' => 'nullable|string',
                'qty' => 'required|integer',
                'producer' => 'required|string|max:255',
            ]);

            Product::create($validatedData);

            // Pop-up sukses
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Produk berhasil ditambahkan!'
            ]);

            return redirect()->route('product-index');
        } catch (\Exception $e) {
            // Pop-up error
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat menambah produk: ' . $e->getMessage()
            ]);

            return redirect()->back();
        }
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('Master-Data.Product-Master.detail-product', compact('product'));
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('master-data.product-master.edit-product', compact('product'));
    }

    /**
     * ✅ Update data dengan pop-up notifikasi
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $request->validate([
                'product_name' => 'required|string|max:255',
                'unit'         => 'required|string|max:255',
                'type'         => 'required|string|max:255',
                'information'  => 'nullable|string',
                'qty'          => 'required|integer|min:1',
                'producer'     => 'required|string|max:255',
            ]);

            $product = Product::findOrFail($id);

            $product->update([
                'product_name' => $request->product_name,
                'unit'         => $request->unit,
                'type'         => $request->type,
                'information'  => $request->information,
                'qty'          => $request->qty,
                'producer'     => $request->producer,
            ]);

            // Pop-up sukses
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Produk berhasil diperbarui!'
            ]);

            return redirect()->route('product-index');
        } catch (\Exception $e) {
            // Pop-up error
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat update: ' . $e->getMessage()
            ]);

            return redirect()->back();
        }
    }

    public function destroy(string $id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Dihapus!',
                'text' => 'Produk berhasil dihapus!'
            ]);

            return redirect()->route('product-index');
        }

        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Gagal!',
            'text' => 'Produk tidak ditemukan!'
        ]);

        return redirect()->route('product-index');
    }
}
