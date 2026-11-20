@extends('layouts.master')

@section('title', 'Plants - '.config('app.name'))

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Plant Inventory') }}</h2>
        <a href="{{ route('reports.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('View reports') }}</a>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold">{{ __('All plant records') }}</h3>
                            <p class="text-sm text-gray-500">{{ $plants->total() }} {{ __('records in inventory') }}</p>
                        </div>
                        <a href="{{ route('plants.create') }}"
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                            {{ __('+ Add Plant') }}
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($plants as $plant)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $plant->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($plant->category) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($plant->unit_price, 2) }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span @class([
                                                'px-2 py-1 rounded-full text-xs',
                                                'bg-green-100 text-green-800' => $plant->stock_status === 'in_stock',
                                                'bg-red-100 text-red-800' => $plant->stock_status === 'out_of_stock',
                                            ])>
                                                {{ str_replace('_', ' ', ucfirst($plant->stock_status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600" title="{{ $plant->description }}">
                                            {{ \Illuminate\Support\Str::limit($plant->description, 60) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right whitespace-nowrap">
                                            <a href="{{ route('plants.show', $plant) }}" class="text-indigo-600 hover:underline">View</a>
                                            <a href="{{ route('plants.edit', $plant) }}" class="ml-3 text-indigo-600 hover:underline">Edit</a>
                                            <form method="POST" action="{{ route('plants.destroy', $plant) }}" class="inline"
                                                  onsubmit="return confirm('Delete this plant record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-3 text-red-600 hover:underline">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                            {{ __('No plants found. Add your first plant record.') }}
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
