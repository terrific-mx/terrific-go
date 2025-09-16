<?php

use App\Models\Link;
use Sqids\Sqids;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects a valid hash to the correct destination URL', function () {
    $link = Link::factory()->create([
        'destination_url' => 'https://example.com',
    ]);

    $this->get("/l/{$link->hashed_id}")->assertRedirect('https://example.com');
});

it('returns a 404 for a nonexistent hash', function () {;
    $hash = (new Sqids())->encode([999999]);

    $this->get("/l/{$hash}")->assertStatus(404);
});

it('returns a 404 for a hash that decodes to nothing', function () {
    $this->get('/l/invalidhash')->assertStatus(404);
});
