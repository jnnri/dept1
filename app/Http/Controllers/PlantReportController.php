<?php

namespace App\Http\Controllers;

use App\Exports\PlantsExport;
use App\Models\Plant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class PlantReportController extends Controller
{
    /**
     * Show the filterable plant report.
     */
    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);

        // The same query builder feeds the screen, the PDF and the Excel file.
        // That guarantees exports always match the selected filters and
        // always include the latest saved changes.
        $plants = $this->buildQuery($filters)->paginate(15)->withQueryString();

        return view('reports.index', [
            'plants' => $plants,
            'filters' => $filters,
            'title' => $this->reportTitle($filters),
            'queryString' => $this->exportQueryString($filters),
        ]);
    }

    /**
     * Export the filtered report as a PDF document.
     */
    public function pdf(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $filters = $this->validatedFilters($request);
        $plants = $this->buildQuery($filters)->get();

        return Pdf::loadView('reports.pdf', [
            'plants' => $plants,
            'filters' => $filters,
            'title' => $this->reportTitle($filters),
        ])
            ->setPaper('a4', 'portrait')
            ->download($this->fileName('pdf', $filters));
    }

    /**
     * Export the filtered report as an Excel workbook.
     */
    public function excel(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $filters = $this->validatedFilters($request);
        $plants = $this->buildQuery($filters)->get();

        return Excel::download(
            new PlantsExport($plants, $this->reportTitle($filters)),
            $this->fileName('xlsx', $filters)
        );
    }

    /**
     * Validate and normalise the filter values coming from the request.
     *
     * @return array{category: string|null, status: string|null, type: string}
     */
    private function validatedFilters(Request $request): array
    {
        $validated = $request->validate([
            'category' => ['nullable', \Illuminate\Validation\Rule::in(Plant::CATEGORIES)],
            'status' => ['nullable', \Illuminate\Validation\Rule::in(Plant::STOCK_STATUSES)],
            'type' => ['nullable', \Illuminate\Validation\Rule::in(['all', 'in_stock', 'out_of_stock', 'category', 'price_list'])],
        ]);

        return [
            'category' => $validated['category'] ?? null,
            'status' => $validated['status'] ?? null,
            'type' => $validated['type'] ?? 'all',
        ];
    }

    /**
     * Build the shared report query.
     *
     * Quick report buttons (in_stock / out_of_stock) set the type, while the
     * filter form sets status explicitly. Both end up as the same constraint.
     */
    private function buildQuery(array $filters): \Illuminate\Database\Eloquent\Builder
    {
        $query = Plant::query();

        $status = $filters['status'];
        if (! $status && in_array($filters['type'], ['in_stock', 'out_of_stock'], true)) {
            $status = $filters['type'];
        }

        if ($status) {
            $query->where('stock_status', $status);
        }

        if ($filters['category']) {
            $query->where('category', $filters['category']);
        }

        // Price lists read best cheapest-first; category reports are grouped
        // visually by sorting on the category column.
        if ($filters['type'] === 'price_list') {
            $query->orderBy('unit_price')->orderBy('name');
        } elseif ($filters['type'] === 'category') {
            $query->orderBy('category')->orderBy('name');
        } else {
            $query->latest();
        }

        return $query;
    }

    /**
     * Human-readable heading describing the active filters.
     */
    private function reportTitle(array $filters): string
    {
        $titles = [
            'all' => 'All Plants Report',
            'in_stock' => 'In-Stock Plants Report',
            'out_of_stock' => 'Out-of-Stock Plants Report',
            'category' => 'Plants by Category Report',
            'price_list' => 'Plant Price List',
        ];

        $title = $titles[$filters['type']] ?? $titles['all'];

        if ($filters['category']) {
            $title .= ' - '.ucfirst($filters['category']);
        }

        if ($filters['status']) {
            $title .= ' - '.str_replace('_', ' ', ucfirst($filters['status']));
        }

        return $title;
    }

    /**
     * Keep only report filters in the export links so both export buttons
     * reproduce exactly what is shown on screen.
     *
     * @return array<string, string>
     */
    private function exportQueryString(array $filters): array
    {
        return array_filter([
            'category' => $filters['category'],
            'status' => $filters['status'],
            'type' => $filters['type'],
        ]);
    }

    /**
     * Safe, readable download name for both export formats.
     */
    private function fileName(string $extension, array $filters): string
    {
        return 'plant-report-'.now()->format('Y-m-d-His').'.'.$extension;
    }
}
