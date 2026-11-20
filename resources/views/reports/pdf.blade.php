<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #222; }
        h1 { font-size: 18px; margin: 0 0 4px 0; }
        h2 { font-size: 12px; margin: 16px 0 6px 0; }
        p.meta { margin: 2px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f2f2f2; font-size: 9px; text-transform: uppercase; }
        .footer { margin-top: 18px; color: #777; font-size: 9px; }
    </style>
</head>
<body>
    <h1>{{ config('app.name') }}</h1>
    <h2>{{ $title }}</h2>

    <p class="meta">Generated: {{ now()->format('Y-m-d H:i:s') }}</p>
    <p class="meta">Total records: {{ $plants->count() }}</p>
    @if ($filters['category'])
        <p class="meta">Category filter: {{ ucfirst($filters['category']) }}</p>
    @endif
    @if ($filters['status'])
        <p class="meta">Stock status filter: {{ str_replace('_', ' ', ucfirst($filters['status'])) }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 22%;">Plant name</th>
                <th style="width: 13%;">Category</th>
                <th style="width: 12%;">Unit price</th>
                <th style="width: 13%;">Stock status</th>
                <th style="width: 35%;">Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($plants as $plant)
                <tr>
                    <td>{{ $plant->id }}</td>
                    <td>{{ $plant->name }}</td>
                    <td>{{ ucfirst($plant->category) }}</td>
                    <td>{{ number_format($plant->unit_price, 2) }}</td>
                    <td>{{ str_replace('_', ' ', ucfirst($plant->stock_status)) }}</td>
                    <td>{{ $plant->description }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No plants match the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">This report contains plant inventory records only. It reflects all saved changes as of the generation time.</p>
</body>
</html>
