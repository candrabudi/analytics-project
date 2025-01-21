<?php

use App\Http\Controllers\AdvertiserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseRawController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\KOLController;
use App\Http\Controllers\KolManagementController;
use App\Http\Controllers\KolMasterController;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\ScrapeEngagementController;
use App\Http\Controllers\ScrapeUsernameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use App\Models\KolManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login/process', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/check-login', function () {
    return response()->json(['loggedIn' => Auth::user() ? true : false]);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chart/advertiser', [DashboardController::class, 'chartAdvertiser'])->name('chart.chartAdvertiser');
    
    Route::get('/advertiser/dashboard', [AdvertiserController::class, 'dashboard'])->name('advertiser.dashboard');
    Route::get('/advertiser/dashboard/data-card', [AdvertiserController::class, 'getCardData'])->name('advertiser.dashboard.data_card');
    Route::get('/advertiser/dashboard/chart-one', [AdvertiserController::class, 'getChartDataOne'])->name('advertiser.dashboard.getChartDataOne');
    Route::get('/advertiser/dashboard/data-scale-up', [AdvertiserController::class, 'getDataScaleUp'])->name('advertiser.dashboard.getDataScaleUp');
    Route::get('/advertiser/dashboard/data-scale-down', [AdvertiserController::class, 'getDataScaleDown'])->name('advertiser.dashboard.getDataScaleDown');
    Route::get('/advertiser/dashboard/data-campaign', [AdvertiserController::class, 'getDataCampaign'])->name('advertiser.dashboard.getDataCampaign');

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/edit/{id}', [UserController::class, 'edit']);
    Route::post('/users', [UserController::class, 'store']);
    Route::post('/users/update/{id}', [UserController::class, 'update']);
    Route::post('/users/delete/{id}', [UserController::class, 'destroy']);

    Route::get('/scrape-username/search', [ScrapeUsernameController::class, 'search']);
    Route::post('/scrape-username/search/store', [ScrapeUsernameController::class, 'storeSearch']);
    Route::post('/scrape-username/insert/account', [ScrapeUsernameController::class, 'storeTiktokAccount']);
    Route::post('/scrape-username/search/update/{id}', [ScrapeUsernameController::class, 'updateSearch']);

    Route::get('/scrape-username/history', [ScrapeUsernameController::class, 'historyScrap']);
    Route::get('/scrape-username/history/detail/{a}', [ScrapeUsernameController::class, 'detailHistory']);
    Route::get('/scrape-username/account/{a}', [ScrapeUsernameController::class, 'account']);

    Route::get('/scrape-username/account/video/{a}', [ScrapeUsernameController::class, 'scrapVideoTiktokAccount']);
    Route::get('/scrape-username/account/video/load/{a}', [ScrapeUsernameController::class, 'loadTiktokAccountVideo']);
    Route::get('/scrape-username/history/load', [ScrapeUsernameController::class, 'loadHistoryScrap']);
    Route::get('/scrape-username/history/detail/load/{a}', [ScrapeUsernameController::class, 'loadDetailHistory']);

    Route::get('/load/scrape-username', [ScrapeUsernameController::class, 'loadSearchResult']);
    Route::post('/api/insertSearchData', [ScrapeUsernameController::class, 'insertSearchData']);
    Route::post('/api/insertAccountData', [ScrapeUsernameController::class, 'insertAccountData']);


    Route::get('/scrap-engagement', [ScrapeEngagementController::class, 'index']);

    Route::get('/database-raw/upload', [DatabaseRawController::class, 'upload'])->name('databae_raw.upload');
    Route::get('/database-raw/list', [DatabaseRawController::class, 'databaseRaw'])->name('databae_raw.list');
    Route::get('/database-raw/list/load', [DatabaseRawController::class, 'loadDatabaseRaw'])->name('databae_raw.list.load');
    Route::post('/process-file', [DatabaseRawController::class, 'processFile'])->name('process.file');


    Route::get('/general-setting', [GeneralSettingController::class, 'index'])->name('general_setting.index');
    Route::post('/settings/createOrUpdate', [GeneralSettingController::class, 'createOrUpdate'])->name('settings.createOrUpdate');


    Route::get('/landingpage/list', [LandingpageController::class, 'list'])->name('landingpages.list');
    Route::get('/landingpage/list/load', [LandingpageController::class, 'loadListLandingpage'])->name('landingpages.list.load');
    Route::post('/landingpage/list/store', [LandingpageController::class, 'store'])->name('landingpages.list.store');
    Route::get('/landingpage/list/edit/{a}', [LandingpageController::class, 'edit'])->name('landingpages.list.edit');
    Route::post('/landingpage/list/update/{a}', [LandingpageController::class, 'update'])->name('landingpages.list.update');
    Route::post('/landingpage/list/destroy/{a}', [LandingpageController::class, 'destroy'])->name('landingpages.list.destroy');

    Route::get('/landingpage/performance', [LandingpageController::class, 'performance'])->name('landingpages.performance');
    Route::get('/landingpage/rank', [LandingpageController::class, 'landingpageRank'])->name('landingpages.landingpageRank');


    Route::get('/kol/master', [KOLController::class, 'master'])->name('kol.master');
    Route::post('/kol/master/store', [KolMasterController::class, 'storeKolMaster'])->name('kol.master.store');
    Route::get('/kol/master/load-list', [KOLController::class, 'loadListKolMaster'])->name('kol.master.loadListKolMaster');


    Route::get('/kol/type-influencer', [KOLController::class, 'typeInfluencer'])->name('kol.type_influencer');

    Route::get('/kol/management', [KolManagementController::class, 'index'])->name('kol.management.index');
    Route::post('/kol/management/store', [KolManagementController::class, 'store'])->name('kol.management.store');
});
