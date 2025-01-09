<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DataRaw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DatabaseRawController extends Controller
{
    public function upload()
    {
        if(Auth::user()->username == "admin") {
            return view('error.maintenance');
        }
        return view('database_raw.upload');
    }

    public function processFile(Request $request)
    {
        $data = $request->input('data');

        if($request->is_delete == 1) {
    
            $existingData = DataRaw::where('upload_date', $request->date)->exists();
    
            if ($existingData) {
                DataRaw::where('upload_date', $request->date)->delete();
            }
        }

        if (isset($data['account_name'])) {
            $store = new DataRaw();
            $store->account_name = $data['account_name'] ?? null;
            $store->campaign_name = $data['campaign_name'] ?? null;
            $store->campaign_budget = $data['campaign_budget'] ?? null;
            $store->amount_spent_idr = $data['amount_spent_(idr)'] ?? null;
            $store->adds_of_payment_info = $data['adds_of_payment_info'] ?? null;
            $store->cost_per_add_of_payment_info = $data['cost_per_add_of_payment_info'] ?? null;
            $store->leads = $data['leads'] ?? null;
            $store->cost_per_lead = $data['cost_per_lead'] ?? null;
            $store->donations = $data['donations'] ?? null;
            $store->reach = $data['reach'] ?? null;
            $store->impressions = $data['impressions'] ?? null;
            $store->cpm = $data['cpm_cost_per_1k_impressions'] ?? null;
            $store->link_clicks = $data['link_clicks'] ?? null;
            $store->cpc = $data['cpc_(cost_per_link_click)'] ?? null;
            $store->purchases = $data['purchases'] ?? null;
            $store->cost_per_purchase = $data['cost_per_purchase'] ?? null;
            $store->adds_to_cart = $data['adds_to_cart'] ?? null;
            $store->cost_per_add_to_cart = $data['cost_per_add_to_cart'] ?? null;
            $store->reporting_starts = $data['reporting_starts'] ?? null;
            $store->reporting_ends = $data['reporting_ends'] ?? null;
            $store->upload_date = $request->date;
            $store->save();

            return response()->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'Data successfully inserted.'
            ], 201);
        }

        return response()->json([
            'status' => 'failed',
            'code' => 400,
            'message' => 'Failed to insert data. Account name is required.'
        ], 400);
    }


    public function databaseRaw()
    {
        return view('database_raw.data');
    }

    public function loadDatabaseRaw(Request $request)
    {
        $search = $request->input('search', '');
    
        $today = now()->toDateString();
    
        $startOfMonth = now()->startOfMonth()->toDateString();
    
        $startDate = $request->input('startdate', $startOfMonth);
        $endDate = $request->input('enddate', $today);
    
        $query = DataRaw::query();

        if ($search !== '') {
            if($search != 'undefined') {
                $query->where('account_name', 'LIKE', '%' . $search . '%');
            }
        }

    

        if ($startDate && !$endDate || $endDate == "undefined") {
            $query->whereDate('upload_date', '=', $startDate);
    
        } else if ($startDate && $endDate) {
            $query->whereDate('upload_date', '>=', $startDate)
                  ->whereDate('upload_date', '<=', $endDate);
        }
    
        $results = $query->select(
                'account_name',
                'campaign_name',
                DB::raw('SUM(campaign_budget) as total_campaign_budget'),
                DB::raw('SUM(amount_spent_idr) as amount_spent_idr'),
                DB::raw('AVG(cost_per_add_of_payment_info) as cost_per_add_of_payment_info'),
                DB::raw('SUM(leads) as leads'),
                DB::raw('AVG(cost_per_lead) as cost_per_lead'),
                DB::raw('SUM(donations) as donations'),
                DB::raw('SUM(reach) as reach'),
                DB::raw('SUM(impressions) as impressions'),
                DB::raw('AVG(cpm) as cpm'),
                DB::raw('SUM(link_clicks) as link_clicks'),
                DB::raw('AVG(cpc) as cpc'),
                DB::raw('SUM(purchases) as purchases'),
                DB::raw('AVG(cost_per_purchase) as cost_per_purchase'),
                DB::raw('SUM(adds_to_cart) as adds_to_cart'),
                DB::raw('AVG(cost_per_add_to_cart) as cost_per_add_to_cart')
            )
            ->groupBy('account_name', 'campaign_name')
            ->orderBy('account_name', 'asc')
            ->get();
    
        // Mengembalikan hasil sebagai JSON response
        return response()->json($results);
    }
    
    
    
    
}
