<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Sqids\Sqids;


class LinkRedirectController extends Controller
{
    public function __invoke($hash)
    {
        $link = Link::where('slug', $hash)->first()
            ?? (Link::find((new Sqids())->decode($hash)[0] ?? null));

        abort_if(!$link, 404);

        return redirect()->away($link->destination_url);
    }
}
