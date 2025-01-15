<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RawTiktokAccount;
use Illuminate\Http\Request;

class KolMasterController extends Controller
{
    public function storeKolMaster(Request $request)
    {
        $data = $request->data;

        foreach($data as $req) {
            $store = new RawTiktokAccount();
            $store->author_id = $req['author_id'];
            $store->unique_id = $req['unique_id'];
            $store->nickname = $req['nickname'];
            $store->follower = $req['follower'];
            $store->following = $req['following'];
            $store->like = $req['like'];
            $store->total_video = $req['total_video'];
            $store->avg_views = $req['average'];
            $store->tier = $req['category'];
            $store->save();
        }

        return response()
            ->json([
                'status' => 'success', 
                'code' => 201, 
                'message' => 'Success insert data raw tiktok account'
            ], 201);
    }
}
