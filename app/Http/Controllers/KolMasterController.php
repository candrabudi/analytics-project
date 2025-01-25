<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AssignTiktokCategory;
use App\Models\RawTiktokAccount;
use App\Models\TiktokCategory;
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
                $store->total_interactions = $req['totalInteractions'];
                $store->save();
                $store->fresh();

                $checkCategory = TiktokCategory::where('name', 'LIKE', '%'.$req['category'].'%')
                    ->first();
                if($checkCategory) {
                    $assignCategory = new AssignTiktokCategory();
                    $assignCategory->raw_tiktok_account_id = $store->id;
                    $assignCategory->tiktok_category_id = $checkCategory->id;
                    $assignCategory->save();
                }
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
