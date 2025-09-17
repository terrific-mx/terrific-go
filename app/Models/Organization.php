<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory;

    use Billable;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'personal' => 'boolean',
        ];
    }

    /**
     * Get the links for the organization.
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    /**
     * Get the user that owns the organization.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the invitations for the organization.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(OrganizationInvitation::class);
    }

    /**
     * Get the members of the organization.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user');
    }

    public function addMember(User $user): void
    {
        $this->members()->attach($user->id);
    }

    public function removeMember(User $user): void
    {
        $this->members()->detach($user->id);

        if ($user->current_organization_id === $this->id) {
            $user->current_organization_id = null;
            $user->save();
        }
    }

    public function isMember(User $user): bool
    {
        return $this->members->contains($user);
    }

    public function inviteMember(string $email): OrganizationInvitation
    {
        return $this->invitations()->create([
            'email' => $email,
        ]);
    }
}
