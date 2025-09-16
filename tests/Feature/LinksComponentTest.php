<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Link;
use Livewire\Volt\Volt;

use Sqids\Sqids;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('allows an authenticated user to access the /links path', function () {
    $user = User::factory()->withPersonalOrganizationAndSubscription()->create();

    actingAs($user)->get('/links')->assertStatus(200);
});

it('allows a user to create a shortened link with a valid destination URL', function () {
    $user = User::factory()->withPersonalOrganizationAndSubscription()->create();
    $organization = $user->currentOrganization;

    Volt::actingAs($user)->test('links')
        ->set('destination_url', 'https://example.com')
        ->call('createLink')
        ->assertHasNoErrors();

    tap(Link::first(), function ($link) use ($organization) {
        expect($link)->not->toBeNull();
        expect($link->destination_url)->toBe('https://example.com');
        expect($link->organization->is($organization))->toBeTrue();
    });
});

it('requires a destination url', function () {
    $user = User::factory()->withPersonalOrganizationAndSubscription()->create();

    Volt::actingAs($user)->test('links')
        ->set('destination_url', '')
        ->call('createLink')
        ->assertHasErrors(['destination_url']);
});

it('validates that the destination url is a valid url', function () {
    $user = User::factory()->withPersonalOrganizationAndSubscription()->create();

    Volt::actingAs($user)->test('links')
        ->set('destination_url', 'not-a-url')
        ->call('createLink')
        ->assertHasErrors(['destination_url']);
});

it('allows a user to create a shortened link with a custom slug', function () {
    $user = User::factory()->withPersonalOrganizationAndSubscription()->create();
    $organization = $user->currentOrganization;

    Volt::actingAs($user)->test('links')
        ->set('destination_url', 'https://custom-slug.com')
        ->set('use_custom_slug', true)
        ->set('slug', 'mycustomslug')
        ->call('createLink')
        ->assertHasNoErrors();

    tap(Link::first(), function ($link) use ($organization) {
        expect($link)->not->toBeNull();
        expect($link->destination_url)->toBe('https://custom-slug.com');
        expect($link->slug)->toBe('mycustomslug');
        expect($link->organization->is($organization))->toBeTrue();
    });
});

it('requires a slug when use_custom_slug is true', function () {
    $user = User::factory()->withPersonalOrganizationAndSubscription()->create();

    Volt::actingAs($user)->test('links')
        ->set('destination_url', 'https://slug-required.com')
        ->set('use_custom_slug', true)
        ->set('slug', '')
        ->call('createLink')
        ->assertHasErrors(['slug']);
});

it('validates that the slug is unique', function () {
    $user = User::factory()->withPersonalOrganizationAndSubscription()->create();
    $organization = $user->currentOrganization;

    Link::factory()->create([
        'organization_id' => $organization->id,
        'slug' => 'notunique',
    ]);

    Volt::actingAs($user)->test('links')
        ->set('destination_url', 'https://not-unique.com')
        ->set('use_custom_slug', true)
        ->set('slug', 'notunique')
        ->call('createLink')
        ->assertHasErrors(['slug']);
});

it('validates that the slug is alphanumeric and dashes only', function () {
    $user = User::factory()->withPersonalOrganizationAndSubscription()->create();

    Volt::actingAs($user)->test('links')
        ->set('destination_url', 'https://badslug.com')
        ->set('use_custom_slug', true)
        ->set('slug', 'bad slug!')
        ->call('createLink')
        ->assertHasErrors(['slug']);
});

it('allows a user to create a shortened link with default hashed id if use_custom_slug is false', function () {
    $user = User::factory()->withPersonalOrganizationAndSubscription()->create();
    $organization = $user->currentOrganization;

    Volt::actingAs($user)->test('links')
        ->set('destination_url', 'https://default-hash.com')
        ->set('use_custom_slug', false)
        ->call('createLink')
        ->assertHasNoErrors();

    tap(Link::first(), function ($link) use ($organization) {
        expect($link)->not->toBeNull();
        expect($link->slug)->toBeNull();
        expect($link->hashed_id)->not->toBeNull();
        expect($link->organization->is($organization))->toBeTrue();
    });
});
