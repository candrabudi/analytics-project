<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AssignTiktokCategory;
use App\Models\GeneralSetting;
use App\Models\KolDataRaw;
use App\Models\RawTiktokAccount;
use App\Models\TiktokCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class KOLController extends Controller
{
    public function master()
    {
        $categories = TiktokCategory::all();
        return view('kol.master.index', compact('categories'));
    }

    public function loadListKolMaster(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');
        $tier = $request->input('categories', []); // Ambil filter kategori
        $categories = $tier ? explode(',', $tier) : [];

        $kols = RawTiktokAccount::when($search, function ($query) use ($search) {
            $query->where('nickname', 'LIKE', "%$search%")
                ->orWhere('unique_id', 'LIKE', "%$search%");
        })
            ->when(!empty($categories) && !in_array('all', $categories), function ($query) use ($categories) {
                // Filter berdasarkan kategori yang dipilih
                $query->whereHas('assignCategory', function ($categoryQuery) use ($categories) {
                    $categoryQuery->whereIn('tiktok_category_id', $categories); // Sesuaikan kolom category_id
                });
            })
            ->with('assignCategory')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, ['*'], 'page', $page);

        // Transform collection to add file URL for front-end
        $kols->getCollection()->transform(function ($kol) {
            $kol->file_url = $kol->file ? url('storage/files/' . $kol->file) : null;
            return $kol;
        });

        // Return response in JSON format
        return response()->json($kols);
    }





    public function typeInfluencer()
    {
        $categories = TiktokCategory::all();
        return view('kol.type_influencer.index', compact('categories'));
    }

    public function editDatabaseRaw($a)
    {
        $rawTiktokAccount = RawTiktokAccount::findOrFail($a);
        $categories = TiktokCategory::all();
        $selectedCategories = $rawTiktokAccount->categories->pluck('id')->toArray();
        return view('kol.type_influencer.edit', compact('rawTiktokAccount', 'selectedCategories', 'categories'));
    }

    public function updateDatabaseRaw(Request $request, $id)
    {
        $rawTiktokAccount = RawTiktokAccount::findOrFail($id);

        $rawTiktokAccount->status_call = $request->input('status_call');
        $rawTiktokAccount->whatsapp_number = $request->input('whatsapp_number');
        $rawTiktokAccount->notes = $request->input('notes');

        if ($request->hasFile('file')) {
            if ($rawTiktokAccount->file) {
                Storage::delete('public/files/' . $rawTiktokAccount->file);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/files', $fileName);
            $rawTiktokAccount->file = $fileName;
        }

        $rawTiktokAccount->update($request->except('categories'));

        if ($request->has('categories')) {
            $rawTiktokAccount->categories()->detach();
            $rawTiktokAccount->categories()->attach($request->categories);
        }

        $rawTiktokAccount->save();
        return redirect()->route('kol.type_influencer')->with('success', 'TikTok account updated successfully!');
    }

    public function scrapeUsernameDatabaseRaw(Request $request)
    {
        $setting = GeneralSetting::first();
        $response = Http::withHeaders([
            'x-rapidapi-host' => 'tiktok-download-video1.p.rapidapi.com',
            'x-rapidapi-key' => $setting->rapid_api_key,
        ])->get('https://tiktok-download-video1.p.rapidapi.com/userInfo', [
                    'unique_id' => '@' . $request->unique_id,
                ]);

        if ($response->successful()) {
            $data = $response->json();
            // return $data;
            $user = $data['data']['user'];
            $stats = $data['data']['stats'];

            $check = RawTiktokAccount::where('author_id', $user['id'])
                ->first();

            if (!$check) {
                $videoResponse = Http::withHeaders([
                    'x-rapidapi-host' => 'tiktok-download-video1.p.rapidapi.com',
                    'x-rapidapi-key' => 'fd95897d1fmsh16cd082ff4db73ep145e8fjsn5adfe90752d1',
                ])->get('https://tiktok-download-video1.p.rapidapi.com/userPublishVideo', [
                            'unique_id' => '@' . $request->unique_id,
                            'count' => 12,
                            'cursor' => 0
                        ]);

                $totalPlayCount = 0;
                $totalDigg = 0;
                $totalComment = 0;
                $totalShare = 0;
                $totalInteractions = 0;
                if ($videoResponse->successful()) {
                    $videos = $videoResponse->json()['data']['videos'];
                    $totalPlayCount = collect($videos)->sum('play_count');
                    $totalDigg = collect($videos)->sum('digg_count');
                    $totalComment = collect($videos)->sum('comment_count');
                    $totalShare = collect($videos)->sum('share_count');
                }

                $totalInteractions = $totalDigg + $totalComment + $totalShare;

                $category = 'nano';
                if ($stats['followerCount'] >= 1000000) {
                    $category = "mega";
                } else if ($stats['followerCount'] >= 100000) {
                    $category = "macro";
                } else if ($stats['followerCount'] >= 10000) {
                    $category = "micro";
                } else if ($stats['followerCount'] >= 1000) {
                    $category = "nano";
                }

                $store = new RawTiktokAccount();
                $store->author_id = $user['id'];
                $store->unique_id = $user['uniqueId'];
                $store->nickname = $user['nickname'];
                $store->follower = $stats['followerCount'];
                $store->following = $stats['followingCount'];
                $store->like = $stats['heartCount'];
                $store->total_video = $stats['videoCount'];
                $store->avg_views = $totalPlayCount / 12;
                $store->total_interactions = $totalInteractions;
                $store->save();
                $store->fresh();

                $checkCategory = TiktokCategory::where('name', 'LIKE', '%' . $category . '%')
                    ->first();
                if ($checkCategory) {
                    $assignCategory = new AssignTiktokCategory();
                    $assignCategory->raw_tiktok_account_id = $store->id;
                    $assignCategory->tiktok_category_id = $checkCategory->id;
                    $assignCategory->save();
                }
            }

            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }


    public function updateField(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:raw_tiktok_accounts,id',
            'field' => 'required|string',
            'value' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm,ogg,pdf,docx,xlsx'
        ]);

        $kolMaster = RawTiktokAccount::find($request->id);

        if ($request->field === 'file_url') {
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('kol_files', 'public');

                if ($kolMaster->file_url) {
                    Storage::disk('public')->delete($kolMaster->file_url);
                }

                $kolMaster->file_url = $filePath;
                $kolMaster->save();

                return response()->json([
                    'success' => true,
                    'newValue' => asset("storage/$filePath")
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No file uploaded.'
                ], 400);
            }
        } else {
            $kolMaster->{$request->field} = $request->value ?? '';
            $kolMaster->save();

            return response()->json([
                'success' => true,
                'newValue' => $kolMaster->{$request->field}
            ]);
        }
    }
}
