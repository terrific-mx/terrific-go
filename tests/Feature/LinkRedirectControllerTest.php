<?php

use App\Models\Link;
use Sqids\Sqids;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('redirects a valid hash to the correct destination URL', function () {
    $link = Link::factory()->create([
        'destination_url' => 'https://example.com',
    ]);

    get("/l/{$link->hashed_id}")->assertRedirect('https://example.com');
});

it('returns a 404 for a nonexistent hash', function () {;
    $hash = (new Sqids())->encode([999999]);

    get("/l/{$hash}")->assertStatus(404);
});

it('returns a 404 for a hash that decodes to nothing', function () {
    get('/l/invalidhash')->assertStatus(404);
});

it('redirects a valid custom slug to the correct destination URL', function () {
    $link = Link::factory()->create([
        'destination_url' => 'https://slug-destination.com',
        'slug' => 'sluggy',
    ]);

    get('/l/sluggy')->assertRedirect('https://slug-destination.com');
});

it('returns a 404 for a nonexistent custom slug', function () {
    get('/l/notarealslug')->assertStatus(404);
});

it('returns a 404 for an invalid slug format', function () {
    get('/l/invalid slug!')->assertStatus(404);
});
