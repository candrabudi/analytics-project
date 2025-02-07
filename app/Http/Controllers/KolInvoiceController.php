<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\KolManagement;
use App\Models\TiktokInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KolInvoiceController extends Controller
{
    // public function index()
    // {
    //     $kolManagements = KolManagement::leftJoin('tiktok_invoices', 'kol_management.id', '=', 'tiktok_invoices.kol_management_id')
    //         ->whereNull('tiktok_invoices.kol_management_id')
    //         ->select('kol_management.*')
    //         ->with('rawTiktokAccount')
    //         ->get();

    //     $kolManagementsEdit = KolManagement::all();
    //     $banks = Bank::all();
    //     return view('kol.invoice.index', compact('kolManagements', 'kolManagementsEdit', 'banks'));
    // }

    public function index()
    {
        $kolManagements = KolManagement::leftJoin('tiktok_invoices', 'kol_management.id', '=', 'tiktok_invoices.kol_management_id')
            ->whereNull('tiktok_invoices.kol_management_id')
            ->select('kol_management.*')
            ->with('rawTiktokAccount')
            ->get();

        $kolManagementsEdit = KolManagement::all();
        $banks = Bank::all();

        $tiktokInvoices = TiktokInvoice::with(['rawTiktokAccount', 'kolManagement', 'bank'])
            ->paginate(10);

        // return $tiktokInvoices;
        return view('kol.invoice.index', compact('kolManagements', 'kolManagementsEdit', 'banks', 'tiktokInvoices'));
    }

    public function load(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');

        $landingpages = TiktokInvoice::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%");
        })
            ->with(['rawTiktokAccount', 'kolManagement', 'bank'])
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($landingpages);
    }

    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'kol_management_id' => 'required|exists:kol_management,id',
            'bank_id' => 'required|exists:banks,id',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'file_upload' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
        ]);

        // Retrieve KOL Management data
        $kolManagement = KolManagement::find($request->kol_management_id);
        if (!$kolManagement) {
            return redirect()->back()->withErrors('KOL management tidak ditemukan.');
        }

        // Check if invoice for this KOL Management already exists
        $existingInvoice = TiktokInvoice::where('kol_management_id', $request->kol_management_id)->first();
        if ($existingInvoice) {
            return redirect()->back()->withErrors('Invoice untuk KOL ini sudah ada.');
        }

        // Handle file upload if any
        $filePath = null;
        if ($request->hasFile('file_upload')) {
            $filePath = $request->file('file_upload')->store('invoices', 'public');
        }

        // Create the Tiktok Invoice
        TiktokInvoice::create([
            'raw_tiktok_account_id' => $kolManagement->raw_tiktok_account_id,
            'kol_management_id' => $request->kol_management_id,
            'bank_id' => $request->bank_id,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'file_upload' => $filePath,
        ]);

        return redirect()->route('kol_invoices.index')->with('success', 'Invoice TikTok berhasil disimpan.');
    }



    public function edit($id)
    {
        $tiktokInvoice = TiktokInvoice::where('id', $id)
            ->with(['rawTiktokAccount', 'kolManagement', 'bank'])
            ->first();
        return $tiktokInvoice;
    }

    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'bank_id' => 'required|exists:banks,id',
    //         'account_name' => 'required|string|max:255',
    //         'account_number' => 'required|numeric',
    //         'file_upload' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
    //     ]);

    //     $tiktokInvoice = TiktokInvoice::findOrFail($id);

    //     if ($request->hasFile('file_upload')) {
    //         $file = $request->file('file_upload');
    //         $filePath = $file->store('uploads/invoices', 'public'); 
    //         $validated['file_upload'] = $filePath;
    //     }

    //     $tiktokInvoice->update($validated);

    //     return response()->json([
    //         'status' => 'success',
    //         'code' => 200,
    //         'message' => 'Success update data'
    //     ]);
    // }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|numeric',
            'status' => 'required|in:pending,process,paid,rejected',
            'file_upload' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);
    
        $tiktokInvoice = TiktokInvoice::findOrFail($id);
    
        if ($request->hasFile('file_upload')) {
            if ($tiktokInvoice->file_upload && Storage::disk('public')->exists($tiktokInvoice->file_upload)) {
                Storage::disk('public')->delete($tiktokInvoice->file_upload);
            }
            $file = $request->file('file_upload');
            $filePath = $file->store('uploads/invoices', 'public');
            $validated['file_upload'] = $filePath;
        }
    
        $tiktokInvoice->update($validated);
        
        return redirect()->back()->with('success', 'TikTok Invoice successfully updated!');
    }
    



    // public function destroy($id)
    // {
    //     $warehouse = TiktokInvoice::findOrFail($id);
    //     $warehouse->delete();

    //     return response()
    //         ->json([
    //             'status' => 'success',
    //             'code' => 200,
    //             'message' => 'Success delete warehouse'
    //         ]);
    // }

    public function destroy($id)
    {
        $tiktokInvoice = TiktokInvoice::findOrFail($id);

        // Delete the file if it exists
        if ($tiktokInvoice->file_upload && Storage::disk('public')->exists($tiktokInvoice->file_upload)) {
            Storage::disk('public')->delete($tiktokInvoice->file_upload);
        }

        // Delete the TikTok invoice
        $tiktokInvoice->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'TikTok Invoice successfully deleted!');
    }

}
