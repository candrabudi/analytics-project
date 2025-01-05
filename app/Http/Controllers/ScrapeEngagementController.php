<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;

class ScrapeEngagementController extends Controller
{
    public function index()
    {
        $generalSetting = GeneralSetting::first();
        return view('scrape_engagement.index', compact('generalSetting'));
    }
}
