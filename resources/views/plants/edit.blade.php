@extends('layouts.master')

@section('title', 'Edit Plant - '.config('app.name'))

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Plant') }}</h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('plants.update', $plant) }}">
                        @csrf
                        @method('PUT')
                        @include('plants._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
