<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Auth;

class ScrapeEngagementController extends Controller
{
    public function index()
    {
        if(Auth::user()->username == "admin") {
            return view('error.maintenance');
        }
        $generalSetting = GeneralSetting::first();
        return view('scrape_engagement.index', compact('generalSetting'));
    }
}
