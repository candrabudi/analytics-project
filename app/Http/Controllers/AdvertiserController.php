<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DataRaw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvertiserController extends Controller
{
    public function dashboard()
    {
        return view('advertiser.dashboard');
    }

    public function getCardData(Request $request)
    {
        function formatShortNumber($number)
        {
            if ($number >= 1000000000) {
                return round($number / 1000000000, 1) . 'B';
            } elseif ($number >= 1000000) {
                return round($number / 1000000, 1) . 'jt';
            } elseif ($number >= 1000) {
                return round($number / 1000, 1) . 'rb';
            } else {
                return $number;
            }
        }

        // SPENDING 
        $todayTotalSpending = DB::table('data_raws')
            ->whereDate('upload_date', Carbon::today())
            ->sum('amount_spent_idr');

        $yesterdayTotalSpending = DB::table('data_raws')
            ->whereDate('upload_date', Carbon::yesterday())
            ->sum('amount_spent_idr');

        $percentageChangeSpending = 0;
        if ($yesterdayTotalSpending > 0) {
            $percentageChangeSpending = (($todayTotalSpending - $yesterdayTotalSpending) / $yesterdayTotalSpending) * 100;
        }

        $cardSpending = [
            'todayTotalSpending' => formatShortNumber($todayTotalSpending),
            'yesterdayTotalSpending' => formatShortNumber($yesterdayTotalSpending),
            'percentageChangeSpending' => round($percentageChangeSpending, 2),
        ];


        // CPR
        $todayTotalCpr = DB::table('data_raws')
            ->whereDate('upload_date', Carbon::today())
            ->sum('cost_per_add_of_payment_info');

        $todayTotalResult = DB::table('data_raws')
            ->whereDate('upload_date', Carbon::today())
            ->sum('leads');

        $yesterdayTotalCpr = DB::table('data_raws')
            ->whereDate('upload_date', Carbon::yesterday())
            ->sum('cost_per_add_of_payment_info');

        $percentageChangeCpr = 0;
        if ($yesterdayTotalCpr > 0) {
            $percentageChangeCpr = (($todayTotalCpr - $yesterdayTotalCpr) / $yesterdayTotalCpr) * 100;
        }

        $cardCpr = [
            'todayTotalCpr' => formatShortNumber($todayTotalCpr),
            'yesterdayTotalCpr' => formatShortNumber($yesterdayTotalCpr),
            'percentageChangeCpr' => round($percentageChangeCpr, 2),
            'todayTotalResult' => $todayTotalResult
        ];


        // IMPRESSION
        $todayTotalImpression = DB::table('data_raws')
            ->whereDate('upload_date', Carbon::today())
            ->sum('impressions');


        $yesterdayTotalImpression = DB::table('data_raws')
            ->whereDate('upload_date', Carbon::yesterday())
            ->sum('impressions');

        $percentageChangeImpression = 0;
        if ($yesterdayTotalImpression > 0) {
            $percentageChangeImpression = (($todayTotalImpression - $yesterdayTotalImpression) / $yesterdayTotalImpression) * 100;
        }

        $cardImpression = [
            'todayTotalImpression' => formatShortNumber($todayTotalImpression),
            'yesterdayTotalImpression' => formatShortNumber($yesterdayTotalImpression),
            'percentageChangeImpression' => round($percentageChangeImpression, 2),
        ];


        // BOUNCE RATE
        $todayTotalBounceRate = DB::table('data_raws')
            ->whereDate('upload_date', Carbon::today())
            ->sum('donations');


        $yesterdayTotalBounceRate = DB::table('data_raws')
            ->whereDate('upload_date', Carbon::yesterday())
            ->sum('donations');

        $percentageChangeBounceRate = 0;
        if ($yesterdayTotalBounceRate > 0) {
            $percentageChangeBounceRate = (($todayTotalBounceRate - $yesterdayTotalBounceRate) / $yesterdayTotalBounceRate) * 100;
        }

        $cardBounceRate = [
            'todayTotalBounceRate' => formatShortNumber($todayTotalBounceRate),
            'yesterdayTotalBounceRate' => formatShortNumber($yesterdayTotalBounceRate),
            'percentageChangeBounceRate' => round($percentageChangeBounceRate, 2),
        ];

        $response = [
            'card_spending' => $cardSpending,
            'card_cpr' => $cardCpr,
            'card_bounce_rate' => $cardBounceRate,
            'card_impression' => $cardImpression
        ];

        return response()->json($response);
    }


    public function getChartDataOne()
    {
        $data = DB::table('data_raws')
            ->select(DB::raw('upload_date, 
                              ROUND(SUM(amount_spent_idr), 0) as total_spent, 
                              ROUND(SUM(cost_per_add_of_payment_info), 0) as total_cost, 
                              SUM(donations) as total_donations, 
                              SUM(impressions) as total_impressions'))
            ->groupBy('upload_date')
            ->orderBy('upload_date')
            ->get();

        return response()->json($data);
    }

    public function getDataScaleUp(Request $request)
    {
        $dates = [
            Carbon::now()->subDays(4)->format('Y-m-d'),
            Carbon::now()->subDays(3)->format('Y-m-d'),
            Carbon::now()->subDays(2)->format('Y-m-d'),
            Carbon::now()->subDays(1)->format('Y-m-d'),
        ];

        $searchTerm = $request->input('search');
        $page = $request->input('page', 1);

        $query = DB::table('data_raws')
            ->select(
                'account_name',
                'campaign_name',
                DB::raw('AVG(cost_per_add_of_payment_info) as average_cost'),
                DB::raw('SUM(adds_of_payment_info) as total_adds'),
                DB::raw('SUM(amount_spent_idr) as spending'),
                DB::raw('COUNT(adds_of_payment_info) as count_adds_of_payment_info')
            )
            ->whereIn('upload_date', $dates)
            ->when($searchTerm, function ($query) use ($searchTerm) {
                return $query->where('account_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('campaign_name', 'like', '%' . $searchTerm . '%');
            })
            ->groupBy('account_name', 'campaign_name')
            ->havingRaw('AVG(cost_per_add_of_payment_info) < 30000')
            ->get();

        if ($query->isEmpty()) {
            return response()->json([
                'data' => [],
                'current_page' => $page,
                'total_pages' => 0,
                'total_items' => 0,
            ]);
        }

        $query = $query->map(function ($item) {
            $item->formatted_average_cost = 'Rp ' . number_format($item->average_cost, 0, ',', '.');
            $item->spending = 'Rp ' . number_format($item->spending, 0, ',', '.');

            return $item;
        });

        $perPage = 10;
        $currentPageData = $query->forPage($page, $perPage);
        $totalPages = ceil($query->count() / $perPage);

        return response()->json([
            'data' => $currentPageData->values(),
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_items' => $query->count(),
        ]);
    }
    
    
    public function getDataScaleDown(Request $request)
    {
        $dates = [
            Carbon::now()->subDays(4)->format('Y-m-d'),
            Carbon::now()->subDays(3)->format('Y-m-d'),
            Carbon::now()->subDays(2)->format('Y-m-d'),
            Carbon::now()->subDays(1)->format('Y-m-d'),
        ];

        $searchTerm = $request->input('search');
        $page = $request->input('page', 1);

        $query = DB::table('data_raws')
            ->select(
                'account_name',
                'campaign_name',
                DB::raw('AVG(cost_per_add_of_payment_info) as average_cost'),
                DB::raw('SUM(adds_of_payment_info) as total_adds'),
                DB::raw('SUM(amount_spent_idr) as spending'),
                DB::raw('COUNT(adds_of_payment_info) as count_adds_of_payment_info')
            )
            ->whereIn('upload_date', $dates)
            ->when($searchTerm, function ($query) use ($searchTerm) {
                return $query->where('account_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('campaign_name', 'like', '%' . $searchTerm . '%');
            })
            ->groupBy('account_name', 'campaign_name')
            ->havingRaw('AVG(cost_per_add_of_payment_info) > 30000')
            ->get();

        if ($query->isEmpty()) {
            return response()->json([
                'data' => [],
                'current_page' => $page,
                'total_pages' => 0,
                'total_items' => 0,
            ]);
        }

        $query = $query->map(function ($item) {
            $item->formatted_average_cost = 'Rp ' . number_format($item->average_cost, 0, ',', '.');
            $item->spending = 'Rp ' . number_format($item->spending, 0, ',', '.');

            return $item;
        });

        $perPage = 10;
        $currentPageData = $query->forPage($page, $perPage);
        $totalPages = ceil($query->count() / $perPage);

        return response()->json([
            'data' => $currentPageData->values(),
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_items' => $query->count(),
        ]);
    }

    public function getDataCampaign(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
    
        $products = DataRaw::when($search, function ($query) use ($search) {
                if ($search != "undefined") {
                    $query->where('account_name', 'LIKE', "%$search%")
                        ->orWhere('campaign_name', 'LIKE', "%$search%");
                }
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('upload_date', [$startDate, $endDate]);
            })
            ->paginate($perPage, ['*'], 'page', $page);
    
        return response()->json($products);
    }
    

}
