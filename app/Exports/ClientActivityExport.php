<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientActivityExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data['clients']);
    }

    public function headings(): array
    {
        return [
            'Client Name',
            'Email',
            'Total Consultations',
            'Total Spent',
            'Last Activity',
        ];
    }

    public function map($row): array
    {
        return [
            $row['name'],
            $row['email'],
            $row['total_consultations'],
            '₱' . number_format($row['total_spent'], 2),
            $row['last_activity'] ? $row['last_activity']->format('Y-m-d H:i:s') : 'Never',
        ];
    }

    public function title(): string
    {
        return 'Client Activity';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
