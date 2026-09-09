<?php

namespace App\Http\Controllers;

use App\Models\ContentPage;
use Illuminate\View\View;

class ContentPageController extends Controller
{
    public function show(ContentPage $page): View
    {
        abort_unless($page->is_published, 404);

        return view('storefront.page', compact('page'));
    }
}

