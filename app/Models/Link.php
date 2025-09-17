<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Sqids\Sqids;

class Link extends Model
{
    /** @use HasFactory<\Database\Factories\LinkFactory> */
    use HasFactory;

    protected $guarded = [];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the hashed id for the link.
     */
    protected function hashedId(): Attribute
    {
        return Attribute::make(
            get: fn () => (new Sqids())->encode([$this->id]),
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

