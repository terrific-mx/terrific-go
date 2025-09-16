<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Sqids\Sqids;


class LinkRedirectController extends Controller
{
    public function __invoke($hash)
    {
        $link = Link::where('slug', $hash)->first();
        if (!$link) {
            $id = (new Sqids())->decode($hash)[0] ?? null;
            $link = $id ? Link::find($id) : null;
        }
        if (!$link) {
            abort(404);
        }
        return redirect()->away($link->destination_url);
    }
}
