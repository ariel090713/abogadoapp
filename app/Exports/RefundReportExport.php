<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RefundReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data['refunds']);
    }

    public function headings(): array
    {
        return [
            'Refund ID',
            'Date',
            'Client Name',
            'Client Email',
            'Lawyer Name',
            'Reason',
            'Refund Amount',
            'Original Amount',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->created_at->format('Y-m-d H:i:s'),
            $row->user?->name ?? 'N/A',
            $row->user?->email ?? 'N/A',
            $row->lawyer?->name ?? 'N/A',
            ucfirst(str_replace('_', ' ', $row->reason)),
            '₱' . number_format($row->refund_amount, 2),
            '₱' . number_format($row->original_amount, 2),
            ucfirst($row->status),
        ];
    }

    public function title(): string
    {
        return 'Refunds';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
