<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::user()){
        return redirect('home');
    }else{
        return view('auth.login');
    }
    
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/analysis', 'AnalyzeController@index')->name('analysis');
    Route::get('/analysis/production', 'AnalyzeController@analysisProduction')->name('analysis-production');
    Route::get('/analysis/ranking', 'AnalyzeController@analysisRanking')->name('analysis-ranking');
    Route::get('/analysis/count/month', 'AnalyzeController@analysisCountPerMonth')->name('analysis-count-month');
    Route::get('/adm/customer', 'PersonController@getAdmCustomers')->name('adm-customers');
    Route::get('/adm/employees', 'PersonController@getAdmEmployees')->name('adm-employees');
    Route::get('/adm/emails', 'PersonController@getAdmEmails')->name('adm-emails');
    Route::get('/bureaus', 'BureauController@index')->name('bureaus');
    Route::post('/bureaus/results', 'BureauController@showSearchResults')->name('bureaus-search-results');
    Route::put('/bureaus/{document}/{bureau}/{client}/{solicitation}/update', 'BureauController@update')->name('bureau-update');
    Route::delete('/bureaus/{document}/{bureau}/{client}/{solicitation}/delete', 'BureauController@delete')->name('bureau-delete');
    Route::get('/bureaus/processes', 'BureauController@getDocumentsWithProcesses')->name('processes');
    Route::get('/bureaus/pendencies', 'BureauController@getDocumentsWithPendencies')->name('pendencies');
    Route::get('/bureaus/protests', 'BureauController@getDocumentsWithProtests')->name('protests');
    Route::get('/bureaus/checks', 'BureauController@getDocumentsWithChecks')->name('checks');
    Route::get('/bureaus/inquiries', 'BureauController@getDocumentsWithInquiries')->name('inquiries');


});

