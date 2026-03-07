<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PayoutReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data['payouts']);
    }

    public function headings(): array
    {
        return [
            'Payout ID',
            'Date',
            'Lawyer Name',
            'Lawyer Email',
            'Amount',
            'Transaction Count',
            'Method',
            'Reference Number',
            'Status',
            'Processed At',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->created_at->format('Y-m-d H:i:s'),
            $row->lawyer?->name ?? 'N/A',
            $row->lawyer?->email ?? 'N/A',
            '₱' . number_format($row->amount, 2),
            $row->transactions->count(),
            ucfirst(str_replace('_', ' ', $row->method ?? 'N/A')),
            $row->reference_number ?? 'N/A',
            ucfirst($row->status),
            $row->processed_at ? $row->processed_at->format('Y-m-d H:i:s') : 'N/A',
        ];
    }

    public function title(): string
    {
        return 'Payouts';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
