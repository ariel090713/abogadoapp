<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConsultationReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data['by_type']);
    }

    public function headings(): array
    {
        return [
            'Consultation Type',
            'Count',
            'Average Amount',
        ];
    }

    public function map($row): array
    {
        return [
            ucfirst(str_replace('_', ' ', $row->consultation_type)),
            $row->count,
            '₱' . number_format($row->avg_amount, 2),
        ];
    }

    public function title(): string
    {
        return 'Consultations';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
