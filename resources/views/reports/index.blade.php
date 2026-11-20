@extends('layouts.master')

@section('title', 'Plant Reports - '.config('app.name'))

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Plant Reports') }}</h2>
@endsection

@section('content')
    @php
        $exportParams = $queryString ? '?'.http_build_query($queryString) : '';
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold">{{ $title }}</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $plants->total() }} {{ __('plant records match the current filters') }}
                    </p>

                    {{-- Quick report presets --}}
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach ([
                            'all' => 'All plants',
                            'in_stock' => 'In stock',
                            'out_of_stock' => 'Out of stock',
                            'category' => 'By category',
                            'price_list' => 'Price list',
                        ] as $type => $label)
                            <a href="{{ route('reports.index', array_merge($queryString, ['type' => $type])) }}"
                               @class([
                                   'px-3 py-2 rounded-md text-sm',
                                   'bg-indigo-600 text-white' => $filters['type'] === $type,
                                   'bg-gray-100 text-gray-700 hover:bg-gray-200' => $filters['type'] !== $type,
                               ])>
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Filters: a plain GET form keeps the URL shareable and lets
                         the PDF/Excel buttons reuse the exact same query string. --}}
                    <form method="GET" action="{{ route('reports.index') }}" class="mt-6 grid gap-4 sm:grid-cols-4 sm:items-end">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category" name="category"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All categories</option>
                                @foreach (\App\Models\Plant::CATEGORIES as $category)
                                    <option value="{{ $category }}" @selected($filters['category'] === $category)>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Stock status</label>
                            <select id="status" name="status"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Any status</option>
                                @foreach (\App\Models\Plant::STOCK_STATUSES as $status)
                                    <option value="{{ $status }}" @selected($filters['status'] === $status)>
                                        {{ str_replace('_', ' ', ucfirst($status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Report type</label>
                            <select id="type" name="type"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ([
                                    'all' => 'All plants',
                                    'in_stock' => 'In stock',
                                    'out_of_stock' => 'Out of stock',
                                    'category' => 'By category',
                                    'price_list' => 'Price list',
                                ] as $type => $label)
                                    <option value="{{ $type }}" @selected($filters['type'] === $type)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                                {{ __('Apply') }}
                            </button>
                            <a href="{{ route('reports.index') }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">
                                {{ __('Clear') }}
                            </a>
                        </div>
                    </form>

                    {{-- Exports reuse the filtered query string. --}}
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('reports.pdf').$exportParams }}"
                           class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">
                            {{ __('Export PDF') }}
                        </a>
                        <a href="{{ route('reports.excel').$exportParams }}"
                           class="px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                            {{ __('Export Excel') }}
                        </a>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plant name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($plants as $plant)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $plant->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($plant->category) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($plant->unit_price, 2) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ str_replace('_', ' ', ucfirst($plant->stock_status)) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600" title="{{ $plant->description }}">
                                            {{ \Illuminate\Support\Str::limit($plant->description, 60) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                            {{ __('No plants match the selected filters.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $plants->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
