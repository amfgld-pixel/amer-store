<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\View\View;

class SettingsHubController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.settings.index', [
            'company' => Company::query()->where('is_active', true)->first()
                ?? Company::query()->first(),
        ]);
    }
}
