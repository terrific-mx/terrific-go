<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Sqids\Sqids;


class LinkRedirectController extends Controller
{
    public function __invoke($hash)
    {
        $id = (new Sqids())->decode($hash)[0] ?? null;

        $link = Link::findOrFail($id);

        return redirect()->away($link->destination_url);
    }
}
