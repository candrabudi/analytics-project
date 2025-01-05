<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
    



}
