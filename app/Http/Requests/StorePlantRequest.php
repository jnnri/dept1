<?php

namespace App\Http\Requests;

use App\Models\Plant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlantRequest extends FormRequest
{
    /**
     * Only authenticated administrators reach this route (see web.php).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Server-side validation rules for a new plant record.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(Plant::CATEGORIES)],
            // numeric + min:0 guarantees a non-negative price on the server,
            // even if the browser's number input is bypassed.
            'unit_price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'stock_status' => ['required', Rule::in(Plant::STOCK_STATUSES)],
        ];
    }
}
