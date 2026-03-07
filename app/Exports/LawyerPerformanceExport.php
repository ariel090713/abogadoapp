<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LawyerPerformanceExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data['lawyers']);
    }

    public function headings(): array
    {
        return [
            'Lawyer Name',
            'Email',
            'Total Consultations',
            'Completed Consultations',
            'Total Revenue',
            'Total Earnings',
            'Average Rating',
            'Total Reviews',
            'Response Rate (%)',
        ];
    }

    public function map($row): array
    {
        return [
            $row['name'],
            $row['email'],
            $row['total_consultations'],
            $row['completed_consultations'],
            '₱' . number_format($row['total_revenue'], 2),
            '₱' . number_format($row['total_earnings'], 2),
            number_format($row['average_rating'], 2),
            $row['total_reviews'],
            number_format($row['response_rate'], 2) . '%',
        ];
    }

    public function title(): string
    {
        return 'Lawyer Performance';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
