<?php

use App\Models\Organization;
use App\Models\User;
use Livewire\Volt\Volt;
use function Pest\Laravel\actingAs;

it('an authenticated user can edit their organization name', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->for($user)
        ->create([
            'name' => 'Old Name',
        ]);

    Volt::actingAs($user)
        ->test('organizations.settings.general', ['organization' => $organization])
        ->set('name', 'New Name')
        ->call('edit')
        ->assertHasNoErrors();

    expect($organization->fresh()->name)->toBe('New Name');
});

it('cannot update organization name to empty', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->for($user)
        ->create([
            'name' => 'Old Name',
        ]);

    Volt::actingAs($user)
        ->test('organizations.settings.general', ['organization' => $organization])
        ->set('name', '')
        ->call('edit')
        ->assertHasErrors(['name' => 'required']);
});

it('forbids non-owners from editing the organization name', function () {
    $owner = User::factory()->create();
    $nonOwner = User::factory()->create();
    $organization = Organization::factory()
        ->for($owner)
        ->create([
            'name' => 'Old Name',
        ]);

    Volt::actingAs($nonOwner)
        ->test('organizations.settings.general', ['organization' => $organization])
        ->assertForbidden();

    expect($organization->fresh()->name)->toBe('Old Name');
});

it('returns a successful response for the organization details page', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->for($user)->create();

    Volt::actingAs($user)
        ->test('organizations.settings.general', ['organization' => $organization])
        ->assertOk();
});

it('forbids organization members (non-owners) from accessing the organization details page', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $organization = Organization::factory()->for($owner)->create();
    $organization->addMember($member);
    $member->switchOrganization($organization);

    Volt::actingAs($member)
        ->test('organizations.settings.general', ['organization' => $organization])
        ->assertForbidden();
});
