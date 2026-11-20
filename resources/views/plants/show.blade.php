@extends('layouts.master')

@section('title', $plant->name.' - '.config('app.name'))

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $plant->name }}</h2>
        <a href="{{ route('plants.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">{{ __('Back to list') }}</a>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <dl class="divide-y divide-gray-200">
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Plant name') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $plant->name }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Category') }}</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($plant->category) }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Unit price') }}</dt>
                            <dd class="text-sm text-gray-900">{{ number_format($plant->unit_price, 2) }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Stock status') }}</dt>
                            <dd class="text-sm text-gray-900">{{ str_replace('_', ' ', ucfirst($plant->stock_status)) }}</dd>
                        </div>
                        <div class="py-3">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Description') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $plant->description }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Last updated') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $plant->updated_at?->format('Y-m-d H:i:s') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex gap-3">
                        <a href="{{ route('plants.edit', $plant) }}"
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                            {{ __('Edit') }}
                        </a>
                        <form method="POST" action="{{ route('plants.destroy', $plant) }}"
                              onsubmit="return confirm('Delete this plant record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">
                                {{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
