<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Exports exactly the collection handed to it by PlantReportController.
 * The controller applies the active filters, so the workbook mirrors the
 * report currently displayed on screen.
 */
class PlantsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    public function __construct(
        private Collection $plants,
        private string $reportTitle = 'Plants Report'
    ) {
    }

    public function collection(): Collection
    {
        return $this->plants;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Plant Name',
            'Category',
            'Unit Price',
            'Stock Status',
            'Description',
            'Created At',
        ];
    }

    /**
     * One row per plant. Values come from the same filtered Eloquent models
     * used by the report table and the PDF.
     */
    public function map($plant): array
    {
        return [
            $plant->id,
            $plant->name,
            ucfirst($plant->category),
            number_format((float) $plant->unit_price, 2, '.', ''),
            str_replace('_', ' ', ucfirst($plant->stock_status)),
            $plant->description,
            $plant->created_at?->format('Y-m-d H:i:s') ?? '',
        ];
    }

    public function title(): string
    {
        return 'Plants';
    }
}
