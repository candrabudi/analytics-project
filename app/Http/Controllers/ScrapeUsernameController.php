<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessTiktokData;
use App\Models\TiktokAccount;
use App\Models\TiktokSearch;
use App\Models\GeneralSetting;
use App\Models\TiktokAccountVideo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ScrapeUsernameController extends Controller
{
    public function search()
    {
        if(Auth::user()->username == "admin") {
            return view('error.maintenance');
        }
        $setting = GeneralSetting::first();
        return view('scrape_username.search', compact('setting'));
    }


    public function storeSearch(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'keyword' => 'required|string|max:255',
            'results' => 'nullable|integer',
            'requests_handled' => 'nullable|integer',
            'requests_total' => 'nullable|integer',
            'started_at' => 'nullable|date',
            'duration_seconds' => 'nullable|integer',
        ]);
    
        if (isset($validatedData['started_at'])) {
            $validatedData['started_at'] = Carbon::parse($validatedData['started_at'])->format('Y-m-d H:i:s');
        }
    
        // Simpan data ke database
        $search = TiktokSearch::create($validatedData);
    
        return response()->json([
            'message' => 'Search data saved successfully!',
            'data' => $search
        ]);
    }

    public function updateSearch(Request $request, $id)
    {
        $validatedData = $request->validate([
            'results' => 'nullable|integer',
            'requests_handled' => 'nullable|integer',
            'requests_total' => 'nullable|integer',
            'duration_seconds' => 'nullable|integer',
            'status' => 'nullable|string'
        ]);

        $search = TiktokSearch::findOrFail($id);

        $search->update($validatedData);

        return response()->json([
            'message' => 'Search data updated successfully!',
            'data' => $search
        ]);
    }

    
    public function insertSearchData(Request $request)
    {
        $keyword = $request->keyword;
        $tiktokSearch = new TiktokSearch();
        $tiktokSearch->keyword = $keyword;
        $tiktokSearch->type = 'username';
        $tiktokSearch->save();
        $tiktokSearch->fresh();
        return response()
            ->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Success insert account',
                'data' => [
                    'tiktok_search_id' => $tiktokSearch->id
                ],
            ]);
    }

    public function storeTiktokAccount(Request $request)
    {
        $store = new TiktokAccount();
        $store->tiktok_search_id = $request->tiktok_search_id;
        $store->author_id = $request->author_id;
        $store->unique_id = $request->unique_id;
        $store->nickname = $request->nickname;
        $store->follower = $request->follower;
        $store->total_video = $request->total_video;
        $store->average = $request->average;
        $store->save();

        return response()
            ->json([
                'status' => 'success',
                'code' => 201, 
                'message' => 'Success insert tiktok account', 
                'data' => []
            ], 201);
    }

    public function loadSearchResult(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $results = TiktokSearch::orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
        $results->getCollection()->transform(function ($account) {
            $account->total_search = $account->getTiktokAccountCountAttribute();
            return $account;
        });
    
        return response()->json($results);
    }

    public function historyScrap()
    {
        if(Auth::user()->username == "admin") {
            return view('error.maintenance');
        }
        return view('scrape_username.history');
    }

    public function loadHistoryScrap(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
    
        $query = TiktokSearch::query();
    
        if ($search) {
            $query->where('keyword', 'like', "%{$search}%");
        }
    
        $results = $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);

        $results->getCollection()->transform(function ($account) {
            $account->total_search = $account->getTiktokAccountCountAttribute();
            return $account;
        });
    
        return response()->json($results);
    }

    public function detailHistory($a) 
    {
        if(Auth::user()->username == "admin") {
            return view('error.maintenance');
        }
        $tiktokSearch = TiktokSearch::where('id', $a)
            ->first();

        return view('scrape_username.history_detail', compact('a', 'tiktokSearch'));
    }

    public function loadDetailHistory(Request $request, $a)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);

        $results = TiktokAccount::where('tiktok_search_id', $a)
            ->where('nickname', 'LIKE', '%'. $request->search.'%')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($results);
    }

    public function account($a)
    {

        if(Auth::user()->username == "admin") {
            return view('error.maintenance');
        }
        $tiktokAccount = TiktokAccount::where('author_id', $a)
            ->first();
        return view('scrape_username.account', compact('tiktokAccount'));
    }

    public function scrapVideoTiktokAccount($a) 
    {
        $setting = GeneralSetting::first();
        $accountTikTok = TiktokAccount::where('tiktok_account_id', $a)
            ->first();
        
        if(!$accountTikTok) {
            return redirect()->route('home');
        }
        $titkokAccountVideo = TiktokAccountVideo::where('tiktok_account_id', $a)
            ->count();

        if($titkokAccountVideo == 0) {
            $response = Http::withHeaders([
                'x-rapidapi-host' => 'tiktok-download-video1.p.rapidapi.com',
                'x-rapidapi-key' => $setting->rapid_api_key
            ])->get('https://tiktok-download-video1.p.rapidapi.com/userPublishVideo', [
                'user_id' => $accountTikTok->tiktok_account_id,
                'count' => 30, 
                'cursor' => 0,
            ]);
    
            $result = $response->json();
            if ($result['code'] == 0) {
                $videos = $result['data']['videos'];

                foreach($videos as $video) {
                    TiktokAccountVideo::create([
                        'tiktok_account_id' => $a,
                        'aweme_id' => $video['aweme_id'],
                        'video_id' => $video['video_id'],
                        'region' => $video['region'],
                        'title' => $video['title'],
                        'cover' => $video['cover'],
                        'duration' => $video['duration'],
                        'play' => $video['play'],
                        'play_count' => $video['play_count'],
                        'digg_count' => $video['digg_count'],
                        'comment_count' => $video['comment_count'],
                        'share_count' => $video['share_count'],
                        'download_count' => $video['download_count'],
                        'collect_count' => $video['collect_count'],
                        'create_time' => $video['create_time'],
                        'is_top' => $video['is_top']
                    ]);
                }
            }
        }

        return redirect()->back();
        
    }

    public function loadTiktokAccountVideo(Request $request, $a)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');

        $products = TiktokAccountVideo::where('tiktok_account_id', $a)
            ->when($search, function ($query) use ($search) {
                $query->where('video_id', 'LIKE', "%$search%")
                    ->orWhere('total_comment', 'LIKE', "%$search%")
                    ->orWhere('total_views', 'LIKE', "%$search%")
                    ->orWhere('total_likes', 'LIKE', "%$search%");
            })
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($products);
    }

    
}
