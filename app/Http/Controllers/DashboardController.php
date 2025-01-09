<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DataRaw;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if(Auth::user()->username == "admin") {
            return view('error.maintenance');
        }
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfDay();

        // Optimize query by fetching all required data in one go
        $data = DataRaw::whereDate('upload_date', '>=', $startDate)
            ->whereDate('upload_date', '<=', $endDate)
            ->selectRaw('
            SUM(leads) as spending, 
            SUM(cost_per_add_of_payment_info) as cost_per_result, 
            SUM(impressions) as impressions, 
            SUM(donations) as bounce_rate, 
            DATE(upload_date) as date
        ')
            ->groupBy('date')
            ->get();

        // Format data for the chart
        $chartAnalytics = [];
        foreach ($data as $item) {
            $chartAnalytics[] = [
                'upload_date' => $item->date,
                'spending' => round($item->spending),
                'impressions' => round($item->impressions) ?? 0,
                'bounce_rate' => round($item->bounce_rate) ?? 0,
                'cost_per_result' => round($item->cost_per_result) ?? 0,
            ];
        }

        // Calculate the sum for each metric
        $spending = $data->sum('spending');
        $costPerResult = $data->sum('cost_per_result');
        $impresion = $data->sum('impressions');
        $bounceRate = $data->sum('bounce_rate');

        // return $chartAnalytics;

        return view('dashboard.index', compact('spending', 'costPerResult', 'impresion', 'bounceRate', 'chartAnalytics'));
    }

    public function chartAdvertiser()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfDay();
    
        // Fetch data (combine all queries into one)
        $data = DataRaw::whereDate('upload_date', '>=', $startDate)
            ->whereDate('upload_date', '<=', $endDate)
            ->selectRaw('
                ROUND(SUM(campaign_budget)) as spending, 
                ROUND(SUM(cost_per_add_of_payment_info)) as cost_per_result, 
                SUM(impressions) as impressions, 
                SUM(donations) as bounce_rate, 
                DATE(upload_date) as date
            ')
            ->groupBy('date')
            ->get();
    
        // Prepare data for the chart
        $dates = $data->pluck('date')->toArray();
        $spending = $data->pluck('spending')->toArray();
        $impressions = $data->pluck('impressions')->toArray();
        $bounceRate = $data->pluck('bounce_rate')->toArray();
        $costPerResult = $data->pluck('cost_per_result')->toArray();
    
        // Return the data as JSON
        return response()->json([
            'spending' => $spending,
            'impressions' => $impressions,
            'bounceRate' => $bounceRate,
            'costPerResult' => $costPerResult,
            'dates' => $dates
        ]);
    }
    

}
