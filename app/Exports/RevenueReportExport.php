<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $data;
    protected $startDate;
    protected $endDate;

    public function __construct($data, $startDate, $endDate)
    {
        $this->data = $data;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return collect($this->data['daily_revenue']);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Total Revenue',
            'Transaction Count',
            'Average Transaction',
        ];
    }

    public function map($row): array
    {
        return [
            $row->date,
            '₱' . number_format($row->total, 2),
            $row->count,
            '₱' . number_format($row->total / $row->count, 2),
        ];
    }

    public function title(): string
    {
        return 'Revenue Report';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
