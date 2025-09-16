<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Link;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;

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

it('redirects the short link URL to the destination URL', function () {
    $link = Link::factory()->create([
        'destination_url' => 'https://example.com',
    ]);

    $response = $this->get("/l/{$link->id}");
    $response->assertRedirect('https://example.com');
});

it('returns a 404 for a non-existent short link', function () {
    $response = $this->get('/l/999999');
    $response->assertStatus(404);
});
