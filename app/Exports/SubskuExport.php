<?php

namespace App\Exports;

use App\Models\Subsku;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SubskuExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    protected $supId;
    protected $productId;
    protected $startDate;
    protected $endDate;

    public function __construct($supId='', $productId='', ?Carbon $startDate, ?Carbon $endDate)
    {
        $this->supId = $supId;
        $this->productId = $productId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Subsku::select([
            'subskus.date',
            'products.sku as psku',
            'subskus.sku',
            'suppliers.name',
            'subskus.qty',
            'subskus.location',
            'subskus.description',
            'subskus.status',
        ])->leftJoin('suppliers', 'subskus.sup_id', '=', 'suppliers.id')
        ->leftJoin('products', 'subskus.product_id', '=', 'products.id');

        if ($this->supId) {
            $query->where('subskus.sup_id', $this->supId);
        }
        if ($this->productId) {
            $query->where('subskus.product_id', $this->productId);
        }
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('subskus.date', [$this->startDate, $this->endDate]);
        } elseif ($this->startDate) {
            $query->where('subskus.date', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->where('subskus.date', '<=', $this->endDate);
        }
        // echo $query->toSql(); exit(0);
        $subskuData = $query->get();

        // Debug statement
        // Log::info('Subsku Data:', $subskuData->toArray());

        return $subskuData;
    }

    public function headings(): array
    {
        return [
            'Date_d_m_y',
            'Main_sku',
            'Sub_sku',
            'Supplier',
            'Size',
            'Location',
            'Description',
            'Status',
        ];
    }

    public function map($subsku): array
    {
        return [
            Carbon::parse($subsku->date)->format('d-m-Y'),
            $subsku->psku,
            $subsku->sku,
            $subsku->name??"Not Found",
            $subsku->qty,
            (string) $subsku->location,
            $subsku->description,
            $subsku->status == 1 ? 'Active' : 'Inactive',
        ];
    }
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_TEXT, // 'F' is the column letter for "Location"
        ];
    }
}
