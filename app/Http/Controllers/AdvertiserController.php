<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DataRaw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AdvertiserController extends Controller
{
    public function dashboard()
    {
        return view('advertiser.dashboard');
    }

   
    public function getFacebookData(Request $request)
    {
        $accessToken = 'EAAVQkiBTycIBOxMRGcBszXvoh4ekZBi5Hppu7ZC0LPbyAFZAxgJpzHWOckbOSkisSX2n6QvKZCXhcR6IXVgZACXACxcmME3DZCfAEOSeHcXMszXCBTHHr5Y8L2pcUh1bP7ZCp70SZBo2ZC2dCezJilmcDjCwZA7ZC8QnJZBlCzlDt23RoQXhuJitMesVzj3ZAep2eu9nFoatlvoYDq1wru5zyXgZDZD'; // Mengambil access token dari .env
        $startDate = $request->query('start_date', '2025-02-05'); // Tanggal default
        $endDate = $request->query('end_date', '2025-02-05');     // Tanggal default
        $actId = $request->query('act_', '1938320656550699');     // ID akun iklan default
    
        // Membentuk URL berdasarkan permintaan
        $url = "https://graph.facebook.com/v22.0/act_" . $actId .
            "?fields=account_id,amount_spent,account_status,name,business," .
            "insights.limit(10).time_range({'since':'" . $startDate . "','until':'" . $endDate . "'})" .
            "{clicks,impressions,spend,reach,date_start,actions,cost_per_action_type}" .
            "&access_token=" . $accessToken;
    
        // Menggunakan cURL untuk mengirimkan request ke Facebook Graph API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Eksekusi cURL dan simpan respons
        $response = curl_exec($ch);
    
        // Cek apakah ada error
        if (curl_errno($ch)) {
            $errorMessage = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => 'Failed to fetch insights data', 'details' => $errorMessage], 500);
        }
    
        // Tutup cURL
        curl_close($ch);
    
        // Konversi respons menjadi array
        $responseData = json_decode($response, true);
    
        // Jika respons sukses, kembalikan data dalam format JSON
        if ($responseData) {
            return response()->json($responseData);
        }
    
        // Jika gagal, kembalikan pesan error
        return response()->json(['error' => 'Failed to fetch insights data', 'details' => $response], 500);
    }


    public function getFacebookDataView(Request $request)
{
    $accessToken = 'EAAVQkiBTycIBO9gkd4zdxuUcyJsCkoRZBZAs3XTk9xUwSULvjubG1XZAIT6pwCu0juQzj29pem5RZB1EHEIjiXIjwVPZCreHi9DLyVS5VsOPvJNJtrH0rDBvsPXsF41ZCkpZChjiuftC3rpHMMXbScFh3AmGowGzZBdYJnizUlx6csMYlfunvUDcRY8uSyfsBtj2e78pRBidvcbapcnVAQZDZD';
    $startDate = $request->query('start_date', now()->toDateString());
    $endDate = $request->query('end_date', now()->toDateString());
    $actIdsFilter = $request->query('act_');

    $cacheKey = 'facebook_ad_accounts';
    $cacheDuration = 60; // Cache 1 jam

    $accountData = Cache::remember($cacheKey, $cacheDuration, function () use ($accessToken) {
        $url = "https://graph.facebook.com/v22.0/me/adaccounts?fields=account_id,name,amount_spent&limit=500&access_token=" . $accessToken;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $accountData = json_decode($response, true);
        $actIds = [];

        if (isset($accountData['data'])) {
            foreach ($accountData['data'] as $account) {
                $actIds[] = [
                    'name' => $account['name'],
                    'account_id' => $account['account_id']
                ];
            }
        }

        return $actIds;
    });

    $actIds = $accountData;

    // Jika tidak ada filter dari user, gunakan semua account_id dari API
    if (!$actIdsFilter) {
        $actIdsFilter = array_column($actIds, 'account_id');
    }

    $allAccountData = [];

    foreach ($actIdsFilter as $actId) {
        $url = "https://graph.facebook.com/v22.0/act_" . $actId . "?fields=account_id,name,insights.time_range({'since':'" . $startDate . "','until':'" . $endDate . "'}){clicks,impressions,spend,reach,date_start,actions,cost_per_action_type}&access_token=" . $accessToken;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            continue;
        }

        curl_close($ch);
        $responseData = json_decode($response, true);

        if (isset($responseData['insights']['data'])) {
            foreach ($responseData['insights']['data'] as $insight) {
                $addToCart = 0;
                $costAddToCart = 0;

                if (isset($insight['actions'])) {
                    foreach ($insight['actions'] as $action) {
                        if ($action['action_type'] === 'add_to_cart') {
                            $addToCart = $action['value'] ?? 0;
                        }
                    }
                }

                if (isset($insight['cost_per_action_type'])) {
                    foreach ($insight['cost_per_action_type'] as $cost) {
                        if ($cost['action_type'] === 'add_to_cart') {
                            $costAddToCart = $cost['value'] ?? 0;
                        }
                    }
                }

                $allAccountData[] = [
                    'date' => $insight['date_start'] ?? 'N/A',
                    'platform' => 'Facebook',
                    'account_name' => $responseData['name'] ?? 'Unknown',
                    'impressions' => $insight['impressions'] ?? 0,
                    'clicks' => $insight['clicks'] ?? 0,
                    'spend' => $insight['spend'] ?? 0,
                    'add_to_cart' => $addToCart,
                    'cost_add_to_cart' => $costAddToCart,
                ];
            }
        }
    }

    return view('advertiser.meta', [
        'allAccountData' => $allAccountData,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'actIds' => $actIds
    ]);
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
