<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Analyze;
use App\Http\Requests\AnalysisPerDayRequest;

class HomeController extends Controller
{
    private $analyzeModel;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->analyzeModel = new Analyze();    
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(AnalysisPerDayRequest $request)
    {                    
        $analysisWeeklyAverage = $this->analyzeModel->getAnalysisWeeklyAverage($request->all());
        $analysisPerDay = $this->analyzeModel->getAnalysisPerDay($request->all());
        return view('home', compact('analysisWeeklyAverage', 'analysisPerDay'));
    }
}
