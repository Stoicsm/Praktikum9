<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsExport implements FromCollection, WithHeadings, WithEvents
{
    /**
     * Ambil data collection untuk diekspor
     */
    public function collection()
    {
        // Ambil semua produk sesuai struktur tabel kamu
        return Product::select(
            'id',
            'product_name',
            'unit',
            'type',
            'information',
            'qty',
            'producer',
            'created_at',
            'updated_at'
        )->get();
    }

    /**
     * Judul kolom Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Product Name',
            'Unit',
            'Type',
            'Information',
            'Qty',
            'Producer',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * Styling / event setelah sheet dibuat
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Sisipkan 2 baris di atas header untuk judul laporan
                $sheet->insertNewRowBefore(1, 2);
                $sheet->setCellValue('A1', 'PT SUSU ALAM JAYA');
                $sheet->setCellValue('A2', 'Rekap Stock Produk Gudang');

                // Merge kolom sesuai jumlah kolom (A sampai I)
                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');

                // Styling judul
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

                // Auto size kolom A sampai I
                foreach (range('A', 'I') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
