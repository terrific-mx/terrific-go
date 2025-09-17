<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sqids\Sqids;

class Link extends Model
{
    /** @use HasFactory<\Database\Factories\LinkFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the organization that owns the link.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope a query to find by short id (slug or decoded hash).
     */
    #[Scope]
    protected function byShortId(Builder $query, string $shortId): void
    {
        $decoded = (new Sqids)->decode($shortId);

        $query->where('slug', $shortId)
            ->orWhere('id', $decoded[0] ?? null);
    }

    /**
     * Get the hashed id for the link.
     */
    protected function hashedId(): Attribute
    {
        return Attribute::make(
            get: fn () => (new Sqids)->encode([$this->id]),
        );
    }

    /**
     * Get the short identifier for the link (slug or hashed id).
     */
    protected function shortId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->slug ?? $this->hashed_id,
        );
    }
}
