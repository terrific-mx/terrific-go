<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Billable, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the organizations owned by the user.
     */
    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }

    /**
     * Organizations where the user is a member (not owner).
     */
    /**
     * Organizations where the user is a member (not owner).
     */
    public function memberOrganizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_user');
    }

    /**
     * All organizations the user owns or is a member of (unique).
     */
    public function allOrganizations()
    {
        return $this->organizations
            ->merge($this->memberOrganizations)
            ->unique('id')
            ->values();
    }

    /**
     * Get the user's current organization.
     */
    /**
     * Get the user's current organization.
     */
    public function currentOrganization(): BelongsTo
    {
        if (is_null($this->current_organization_id)) {
            $this->assignPersonalOrganizationAsCurrent();

            $this->fresh();
        }

        return $this->belongsTo(Organization::class, 'current_organization_id');
    }

    /**
     * Assign the user's personal organization as their current organization.
     */
    private function assignPersonalOrganizationAsCurrent(): void
    {
        tap($this->organizations()->where('personal', true)->first(), function ($personalOrg) {
            if ($personalOrg) {
                $this->current_organization_id = $personalOrg->id;
                $this->save();
            }
        });
    }

    /**
     * Switch the user's current organization.
     */
    public function switchOrganization(Organization $organization): void
    {
        $this->current_organization_id = $organization->id;

        $this->save();
    }
}
