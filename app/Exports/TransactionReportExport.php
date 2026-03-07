<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data['transactions']);
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Date',
            'Client Name',
            'Client Email',
            'Lawyer Name',
            'Type',
            'Amount',
            'Platform Fee',
            'Lawyer Payout',
            'Status',
            'Payment Method',
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
            ucfirst(str_replace('_', ' ', $row->type)),
            '₱' . number_format($row->amount, 2),
            '₱' . number_format($row->platform_fee, 2),
            '₱' . number_format($row->lawyer_payout, 2),
            ucfirst($row->status),
            ucfirst(str_replace('_', ' ', $row->payment_method ?? 'N/A')),
        ];
    }

    public function title(): string
    {
        return 'Transactions';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
