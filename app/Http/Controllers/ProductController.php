<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function exportJpg()
{
    // Ubah variabel menjadi $products biar sesuai dengan compact()
    $products = Product::all();

    $firstProduct = Product::orderBy('created_at', 'asc')->first();
    $lastProduct  = Product::orderBy('created_at', 'desc')->first();

    $startDate = $firstProduct ? $firstProduct->created_at->format('d/m/Y') : null;
    $endDate   = $lastProduct ? $lastProduct->created_at->format('d/m/Y') : null;

    // Perbaiki variabel di compact()
    $html = view('master-data.product-master.export-product', compact('products', 'startDate', 'endDate'))->render();

    $path = storage_path('app/public/product.jpg');

    Browsershot::html($html)
        ->setScreenshotType('jpeg')
        ->windowSize(1200, 800)
        ->save($path);

    return response()->download($path);
}

public function exportPdf()
{
    // Sama seperti di atas — ganti $data jadi $products
    $products = Product::all();

    $firstProduct = Product::orderBy('created_at', 'asc')->first();
    $lastProduct  = Product::orderBy('created_at', 'desc')->first();

    $startDate = $firstProduct ? $firstProduct->created_at->format('d/m/Y') : null;
    $endDate   = $lastProduct ? $lastProduct->created_at->format('d/m/Y') : null;
    
    $pdf = Pdf::loadView('master-data.product-master.export-product', compact('products', 'startDate', 'endDate'));
    return $pdf->download('product.pdf');
}


    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
    
    public function index()
{
    $data = Product::paginate(10);

    $firstProduct = Product::orderBy('created_at', 'asc')->first();
    $lastProduct  = Product::orderBy('created_at', 'desc')->first();

    $startDate = $firstProduct ? $firstProduct->created_at->format('d/m/Y') : null;
    $endDate   = $lastProduct ? $lastProduct->created_at->format('d/m/Y') : null;

    return view('master-data.product-master.index-product', compact('data', 'startDate', 'endDate'));
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
