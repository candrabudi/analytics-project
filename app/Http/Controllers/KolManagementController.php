<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KolManagement;
use App\Models\RawTiktokAccount;
use Illuminate\Http\Request;

class KolManagementController extends Controller
{
    public function index()
    {
        $rawTikTokAccounts = RawTiktokAccount::all();
        return view('kol.management.index', compact('rawTikTokAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raw_tiktok_account_id' => 'required|exists:raw_tiktok_accounts,id',
            'pic_id' => 'required|string',
            'platform' => 'required|string',
            'engagement_rate' => 'required|numeric',
            'cpv' => 'required|numeric',
            'deal_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $existingAccount = KolManagement::where('raw_tiktok_account_id', $validated['raw_tiktok_account_id'])
            ->where('platform', $validated['platform'])
            ->first();

        if ($existingAccount) {
            return redirect()->back()->with('error', 'Data sudah ada.');
        }
        KolManagement::create([
            'raw_tiktok_account_id' => $validated['raw_tiktok_account_id'],
            'pic_id' => $validated['pic_id'],
            'platform' => $validated['platform'],
            'engagement_rate' => $validated['engagement_rate'],
            'cpv' => $validated['cpv'],
            'deal_date' => $validated['deal_date'],
            'notes' => $validated['notes'],
        ]);
        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }
}
