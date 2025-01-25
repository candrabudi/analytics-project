<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KolDataRaw;
use App\Models\RawTiktokAccount;
use App\Models\TiktokCategory;
use Illuminate\Http\Request;
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
    
        $kols = RawTiktokAccount::when($search, function ($query) use ($search) {
                $query->where('nickname', 'LIKE', "%$search%")
                    ->orWhere('unique_id', 'LIKE', "%$search%");
            })
            ->paginate($perPage, ['*'], 'page', $page);
    
        // Menambahkan file_url dengan asset() ke dalam response data
        $kols->getCollection()->transform(function ($kol) {
            // Menggunakan asset untuk menghasilkan URL yang dapat diakses
            $kol->file_url = $kol->file ? url('storage/files/' . $kol->file) : null;
            return $kol;
        });
    
        return response()->json($kols);
    }
    
    

    public function typeInfluencer()
    {
        $categories = TiktokCategory::all();
        return view('kol.type_influencer.index', compact('categories'));
    }

    public function editDatabaseRaw($a)
    {
        $rawTiktokAccount = RawTiktokAccount::where('id', $a)
            ->first();

        return view('kol.type_influencer.edit', compact('rawTiktokAccount'));
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

        $rawTiktokAccount->save();
        return redirect()->route('kol.type_influencer')->with('success', 'TikTok account updated successfully!');
    }
}
