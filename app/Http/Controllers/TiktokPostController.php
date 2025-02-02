<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\GeneralSetting;
use App\Models\KolManagement;
use App\Models\TiktokProgressPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TiktokPostController extends Controller
{
    public function index()
    {
        $kolManagements = KolManagement::leftJoin('tiktok_invoices', 'kol_management.id', '=', 'tiktok_invoices.kol_management_id')
            ->whereNull('tiktok_invoices.kol_management_id')
            ->select('kol_management.*')
            ->with('rawTiktokAccount')
            ->get();

        $kolManagementsEdit = KolManagement::all();
        $banks = Bank::all();
        return view('kol.post.index', compact('kolManagements', 'kolManagementsEdit', 'banks'));
    }

    public function load(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');

        $landingpages = TiktokProgressPost::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%");
        })
        ->with(['rawTiktokAccount', 'kolManagement'])
        ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($landingpages);
    }

    public function store(Request $request)
    {
        $setting = GeneralSetting::first();
        $validatedData = $request->validate([
            'kol_management_id' => 'required|exists:kol_management,id',
            'title' => 'required|string|max:255',
        ]);

        $kolManagement = KolManagement::find($request->kol_management_id);
        if (!$kolManagement) {
            return redirect()->back()->withErrors('KOL management tidak ditemukan.')->withInput();
        }

        $targer_views = round($kolManagement->target_views / $kolManagement->deal_post);

        $checkPost = TiktokProgressPost::where('kol_management_id', $kolManagement->id)
            ->get();
        if (count($checkPost) >= $kolManagement->deal_post) {
            return redirect()->back()->withErrors('Post target sudah sesuai deal post.')->withInput();
        }

        $views = 0;
        $likes = 0;
        $comments = 0;
        $shares = 0;
        $saves = 0;
        $create_time = null;

        if ($request->link_post != null) {
            $videoUrl = $request->link_post;
            $apiUrl = 'https://tiktok-download-video1.p.rapidapi.com/getVideo';
            $headers = [
                'x-rapidapi-host' => 'tiktok-download-video1.p.rapidapi.com',
                'x-rapidapi-key'  => $setting->rapid_api_key,
            ];
            $params = [
                'url' => $videoUrl,
                'hd'  => 1,
            ];

            $response = Http::withHeaders($headers)->get($apiUrl, $params);

            if ($response->successful()) {
                $data = $response->json()['data'];
                $views = $data['play_count'];
                $likes = $data['digg_count'];
                $comments = $data['comment_count'];
                $shares = $data['share_count'];
                $saves = $data['collect_count'];
                $timestamps = $data['create_time'];
                $create_time = Carbon::createFromTimestamp($timestamps)->format('Y-m-d');
            }
        }

        TiktokProgressPost::create([
            'raw_tiktok_account_id' => $kolManagement->raw_tiktok_account_id,
            'kol_management_id' => $request->kol_management_id,
            'title' => $request->title,
            'link_post' => $request->link_post ?? null,
            'deadline' => $request->deadline,
            'target_views' => $targer_views,
            'date_post' => $create_time,
            'brief' => $request->brief,
            'views' => $views,
            'likes' => $likes,
            'shares' => $shares,
            'comments' => $comments,
            'saves' => $saves,
        ]);

        return redirect()->route('kol_progress_posts.index')->with('success', 'Invoice TikTok berhasil disimpan.');
    }



    public function edit($id)
    {
        $tiktokInvoice = TiktokProgressPost::where('id', $id)
            ->with(['rawTiktokAccount', 'kolManagement'])
            ->first();
        return $tiktokInvoice;
    }

    public function update(Request $request, $id)
    {
        $setting = GeneralSetting::first();
    
        $validatedData = $request->validate([
            'kol_management_id' => 'required|exists:kol_management,id',
            'title' => 'required|string|max:255',
            'link_post' => 'nullable|url',
            'deadline' => 'nullable|date',
            'target_views' => 'nullable|integer',
            'brief' => 'nullable|string',
        ]);
    
        $tiktokProgressPost = TiktokProgressPost::findOrFail($id);
    
        $views = $tiktokProgressPost->views;
        $likes = $tiktokProgressPost->likes;
        $comments = $tiktokProgressPost->comments;
        $shares = $tiktokProgressPost->shares;
        $saves = $tiktokProgressPost->saves;
        $create_time = $tiktokProgressPost->date_post;
    
        if (!empty($request->link_post)) {
            $videoUrl = $request->link_post;
            $apiUrl = 'https://tiktok-download-video1.p.rapidapi.com/getVideo';
            $headers = [
                'x-rapidapi-host' => 'tiktok-download-video1.p.rapidapi.com',
                'x-rapidapi-key'  => $setting->rapid_api_key,
            ];
            $params = ['url' => $videoUrl, 'hd' => 1];
            $response = Http::withHeaders($headers)->get($apiUrl, $params);
    
            if ($response->successful()) {
                $data = $response->json()['data'];
                $views = $data['play_count'];
                $likes = $data['digg_count'];
                $comments = $data['comment_count'];
                $shares = $data['share_count'];
                $saves = $data['collect_count'];
                $create_time = Carbon::createFromTimestamp($data['create_time'])->format('Y-m-d');
            }
        }
    
        $tiktokProgressPost->update([
            'raw_tiktok_account_id' => KolManagement::find($request->kol_management_id)->raw_tiktok_account_id,
            'kol_management_id' => $request->kol_management_id,
            'title' => $request->title,
            'link_post' => $request->link_post,
            'deadline' => $request->deadline,
            'target_views' => $request->target_views,
            'date_post' => $create_time,
            'brief' => $request->brief,
            'views' => $views,
            'likes' => $likes,
            'shares' => $shares,
            'comments' => $comments,
            'saves' => $saves,
        ]);
    
        return redirect()->route('kol_progress_posts.index')->with('success', 'Invoice TikTok berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $warehouse = TiktokProgressPost::findOrFail($id);
        $warehouse->delete();

        return response()
            ->json([
                'status' => 'success', 
                'code' => 200, 
                'message' => 'Success delete warehouse'
            ]);
    }
}
