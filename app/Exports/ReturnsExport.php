<?php

namespace App\Exports;


use Illuminate\Support\Carbon;
use App\Models\Returns;
// use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReturnsExport implements FromCollection, WithHeadings, WithMapping
{

    protected $customerId;
    protected $startDateTime;
    protected $endDateTime;
    public function __construct($customerId='', ?Carbon $startDateTime, ?Carbon $endDateTime)
    {
        $this->customerId = $customerId;
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
    }
    public function collection()
    {
        $query = Returns::select([
            'returns.date',
            'products.sku',
            'returns.s_sku',
            'returns.size',
            'returns.location',
            'customers.name',
        ])->join('customers', 'returns.customer_id', '=', 'customers.id')
        ->leftJoin('products', 'returns.m_sku', '=', 'products.id');
        
        if ($this->customerId) {
            $query->where('returns.customer_id', $this->customerId);
        }
        if ($this->startDateTime && $this->endDateTime) {
            $query->whereBetween('date', [$this->startDateTime, $this->endDateTime]);
        } elseif ($this->startDateTime) {
            $query->where('date', '>=', $this->startDateTime);
        } elseif ($this->endDateTime) {
            $query->where('date', '<=', $this->endDateTime);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Date_d_m_y',
            'Main_sku',
            'Sub_sku',
            'Size',
            'Customer',
            'Location',
        ];
    }

    public function map($returns): array
    {
        return [
            Carbon::parse($returns->date)->format('d-m-Y'),
            $returns->sku,
            $returns->s_sku,
            $returns->size,
            $returns->name,
            $returns->location,
        ];
    }
}
