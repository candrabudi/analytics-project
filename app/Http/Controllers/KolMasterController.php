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
            $check = RawTiktokAccount::where('author_id', $req['authorID'])
                ->first();

            if(!$check) {
                $store = new RawTiktokAccount();
                $store->author_id = $req['authorID'];
                $store->unique_id = $req['uniqueID'];
                $store->nickname = $req['nickname'];
                $store->follower = $req['follower'];
                $store->following = $req['following'];
                $store->like = $req['like'];
                $store->total_video = $req['totalVideo'];
                $store->avg_views = $req['average'];
                $store->tier = $req['category'];
                $store->save();
            }
        }

        return response()
            ->json([
                'status' => 'success', 
                'code' => 201, 
                'message' => 'Success insert data raw tiktok account'
            ], 201);
    }
}
