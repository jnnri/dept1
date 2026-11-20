<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plant extends Model
{
    /** @use HasFactory<\Database\Factories\PlantFactory> */
    use HasFactory;

    /** Allowed plant categories (also used by validation and the forms). */
    public const CATEGORIES = ['ornamental', 'flowering', 'succulent', 'herb'];

    /** Allowed stock status values. */
    public const STOCK_STATUSES = ['in_stock', 'out_of_stock'];

    /**
     * Fields that may be set from a request.
     *
     * user_id is deliberately excluded: it is always derived from the
     * authenticated user in the controller, never from client input.
     */
    protected $fillable = [
        'name',
        'category',
        'unit_price',
        'description',
        'stock_status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
        ];
    }

    /**
     * The administrator who created this plant record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
