<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KOLController extends Controller
{
    public function master()
    {
        return view('kol.master.index');
    }
}
