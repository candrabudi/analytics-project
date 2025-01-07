<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdvertiserLandingpage;
use App\Models\DataRaw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingpageController extends Controller
{
    public function list()
    {
        return view('landingpage.list');
    }

    public function loadListLandingpage(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');

        $landingpages = AdvertiserLandingpage::when($search, function ($query) use ($search) {
            $query->where('code', 'LIKE', "%$search%")
                ->orWhere('link', 'LIKE', "%$search%");
        })
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($landingpages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'link' => 'required|url|max:255',
            'landingpage_status' => 'required|in:active,inactive',
            'cta_status' => 'required|in:active,inactive',
        ]);

        $landingPage = new AdvertiserLandingpage();
        $landingPage->code = $request->input('code');
        $landingPage->link = $request->input('link');
        $landingPage->landingpage_status = $request->input('landingpage_status');
        $landingPage->cta_status = $request->input('cta_status');
        $landingPage->save();

        return redirect()->back()->with('success', 'Landing page created successfully.');
    }

    public function edit($id)
    {
        $landingpage = AdvertiserLandingpage::findOrFail($id);
        return response()->json($landingpage);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'link' => 'required|url|max:255',
            'landingpage_status' => 'required|in:active,inactive',
            'cta_status' => 'required|in:active,inactive',
        ]);

        $landingpage = AdvertiserLandingpage::findOrFail($id);
        $landingpage->code = $request->input('code');
        $landingpage->link = $request->input('link');
        $landingpage->landingpage_status = $request->input('landingpage_status');
        $landingpage->cta_status = $request->input('cta_status');
        $landingpage->save();

        return redirect()->back()->with('success', 'Landing page updated successfully.');
    }

    public function destroy($id)
    {
        $landingpage = AdvertiserLandingpage::findOrFail($id);
        $landingpage->delete();

        return response()->json(['success' => 'Landing page deleted successfully.']);
    }


    public function performance(Request $request)
    {
        $landingpages = AdvertiserLandingpage::get();
    
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
    
        $datas = [];
        
        foreach ($landingpages as $lp) {
            $performances = DataRaw::where('campaign_name', 'LIKE', '%' . $lp->code . '%')
                ->whereYear('upload_date', $year)
                ->whereMonth('upload_date', $month)
                ->select(
                    'upload_date', 
                    DB::raw('SUM(cost_per_add_of_payment_info) as total_performance'),
                    DB::raw('SUM(amount_spent_idr) as amount_spent'),
                    DB::raw('SUM(adds_of_payment_info) as contact')
                )
                ->groupBy('upload_date')
                ->get()
                ->keyBy('upload_date'); 
    
            $mappedPerformances = [];
            
            $startOfMonth = Carbon::create($year, $month)->startOfMonth();
            $endOfMonth = Carbon::create($year, $month)->endOfMonth();
            
            for ($date = $startOfMonth; $date <= $endOfMonth; $date->addDay()) {
                $formattedDate = $date->format('Y-m-d');
            
                if ($performances->has($formattedDate)) {
                    $mappedPerformances[] = [
                        'tanggal' => $formattedDate,
                        'total_performance' => $performances[$formattedDate]->total_performance,
                        'amount_spent' => $performances[$formattedDate]->amount_spent,
                        'contact' => $performances[$formattedDate]->contact,
                    ];
                } else {
                    $mappedPerformances[] = [
                        'tanggal' => $formattedDate,
                        'total_performance' => '-',
                        'amount_spent' => '-',
                        'contact' => '-',
                    ];
                }
            }
            
            $datas[] = [
                'code' => $lp->code,
                'link' => $lp->link,
                'performances' => $mappedPerformances,
            ];
        }
        
        return view('landingpage.performance', compact('datas', 'selectedMonth', 'selectedYear'));
    }
    

    public function landingpageRank(Request $request)
    {
        $landingpages = AdvertiserLandingpage::get();
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
    
        $datas = [];
        $totalSpent = 0;
    
        foreach ($landingpages as $lp) {
            $totalSpent += DataRaw::where('campaign_name', 'LIKE', '%' . $lp->code . '%')
                ->whereYear('upload_date', $year)
                ->whereMonth('upload_date', $month)
                ->sum('amount_spent_idr');
        }
    
        foreach ($landingpages as $lp) {
            $performance = DataRaw::where('campaign_name', 'LIKE', '%' . $lp->code . '%')
                ->whereYear('upload_date', $year)
                ->whereMonth('upload_date', $month)
                ->select(
                    DB::raw('SUM(cost_per_add_of_payment_info) as total_performance'),
                    DB::raw('SUM(amount_spent_idr) as amount_spent'),
                    DB::raw('SUM(adds_of_payment_info) as contact')
                )
                ->groupBy('campaign_name')
                ->first();
    
            if ($performance) {
                $usedSpendPercentage = round($performance->amount_spent / $totalSpent * 100, 2);
    
                $datas[] = [
                    'code' => $lp->code,
                    'link' => $lp->link,
                    'total_performance' => round($performance->total_performance),
                    'amount_spent' => round($performance->amount_spent),
                    'used_spend' => $usedSpendPercentage,
                ];
            } else {
                $datas[] = [
                    'code' => $lp->code,
                    'link' => $lp->link,
                    'total_performance' => 0,
                    'amount_spent' => 0,
                    'used_spend' => 0,
                ];
            }
        }
    
        usort($datas, function($a, $b) {
            if ($a['amount_spent'] == 0 && $b['amount_spent'] > 0) {
                return 1;
            }
            if ($b['amount_spent'] == 0 && $a['amount_spent'] > 0) {
                return -1;
            }
            return $a['amount_spent'] <=> $b['amount_spent'];
        });
        return $datas;
    }


}
