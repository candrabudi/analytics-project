<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KolDataRaw;
use Illuminate\Http\Request;

class KOLController extends Controller
{
    public function master()
    {
        return view('kol.master.index');
    }

    public function loadListKolMaster(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');
        $tier = $request->input('tier', '');

        $kols = KolDataRaw::when($search, function ($query) use ($search) {
                $query->where('nickname', 'LIKE', "%$search%")
                    ->orWhere('unique_id', 'LIKE', "%$search%");
            })
            ->when($tier, function ($query) use ($tier) {
                $query->where('tier', $tier);
            })
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($kols);
    }

    public function typeInfluencer()
    {
        return view('kol.type_influencer.index');
    }
    
}
