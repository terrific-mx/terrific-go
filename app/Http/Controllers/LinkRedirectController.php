<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LinkRedirectController extends Controller
{
    public function __invoke($id)
    {
        $link = \App\Models\Link::find($id);
        if (! $link) {
            abort(404);
        }
        return redirect()->away($link->destination_url);
    }
}
