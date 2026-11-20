<div>
    <x-input-label for="name" :value="__('Plant name')" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                  :value="old('name', $plant->name ?? '')" required autofocus />
    @error('name')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mt-4">
    <x-input-label for="category" :value="__('Category')" />
    <select id="category" name="category" required
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="">-- Select a category --</option>
        @foreach ($categories as $category)
            <option value="{{ $category }}" @selected(old('category', $plant->category ?? '') === $category)>
                {{ ucfirst($category) }}
            </option>
        @endforeach
    </select>
    @error('category')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mt-4">
    <x-input-label for="unit_price" :value="__('Unit price')" />
    <x-text-input id="unit_price" name="unit_price" type="number" step="0.01" min="0"
                  class="mt-1 block w-full" :value="old('unit_price', $plant->unit_price ?? '')" required />
    @error('unit_price')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mt-4">
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4" required
              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $plant->description ?? '') }}</textarea>
    @error('description')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="mt-4">
    <x-input-label for="stock_status" :value="__('Stock status')" />
    <select id="stock_status" name="stock_status" required
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        @foreach ($statuses as $status)
            <option value="{{ $status }}" @selected(old('stock_status', $plant->stock_status ?? 'in_stock') === $status)>
                {{ str_replace('_', ' ', ucfirst($status)) }}
            </option>
        @endforeach
    </select>
    @error('stock_status')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="flex items-center gap-4 mt-6">
    <x-primary-button>{{ __('Save') }}</x-primary-button>
    <a href="{{ url()->previous() }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
</div>
