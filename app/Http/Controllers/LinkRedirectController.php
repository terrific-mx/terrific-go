<?php

namespace App\Http\Controllers;

use App\Models\Link;

class LinkRedirectController extends Controller
{
    public function __invoke($hash)
    {
        $link = Link::byShortId($hash)->firstOrFail();

        return redirect()->away($link->destination_url);
    }
}
