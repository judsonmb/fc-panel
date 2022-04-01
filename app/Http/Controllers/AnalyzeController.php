<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AnalyzeRequest;
use Illuminate\Support\Facades\Http;
use App\Analyze;
use App\Solicitation;
use App\Person;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnalysisExport;
use App\Exports\CountPerMonthExport;


class AnalyzeController extends Controller
{
    private $analyzeModel;
    private $solicitationModel;
    private $personModel;

    public function __construct()
    {
        $this->analyzeModel = new Analyze();
        $this->solicitationModel = new Solicitation();    
        $this->personModel = new Person();
    }

    public function index(AnalyzeRequest $request)
    {    
        if(!$request->input('export')){
            
            $customers = $this->personModel->getCustomers();
            
            if($request['product'] == '%'){
                $fcanalisePlusCPFs = $this->analyzeModel->getProcessedDocumentsProduct($request->all(), 1);

                $fcrendaCPFs = $this->analyzeModel->getProcessedDocumentsProduct($request->all(), 2);

                $fcanaliseCPFs = $this->analyzeModel->getProcessedDocumentsProduct($request->all(), 3);

                $fccompanyCNPJs = $this->analyzeModel->getProcessedDocumentsProduct($request->all(), 4);
                
                $fcanalisePlusRevenue = $this->analyzeModel->getProductRevenue($request->all(), 1);  

                $fcrendaRevenue = $this->analyzeModel->getProductRevenue($request->all(), 2);
                
                $fcanaliseRevenue = $this->analyzeModel->getProductRevenue($request->all(), 3); 
                
                $fccompanyRevenue = $this->analyzeModel->getProductRevenue($request->all(), 4);  
            }else{
                $fcanalisePlusCPFs = ($request['product'] == "1") ? $this->analyzeModel->getProcessedDocumentsProduct($request->all(), 1) : 0;

                $fcrendaCPFs = ($request['product'] == "2") ? $this->analyzeModel->getProcessedDocumentsProduct($request->all(), 2) : 0;

                $fcanaliseCPFs = ($request['product'] == "3") ? $this->analyzeModel->getProcessedDocumentsProduct($request->all(), 3) : 0;

                $fccompanyCNPJs = ($request['product'] == "4") ? $this->analyzeModel->getProcessedDocumentsProduct($request->all(), 4) : 0;
            
                $fcanalisePlusRevenue = ($request['product'] == "1") ? $this->analyzeModel->getProductRevenue($request->all(), 1) : 0;  

                $fcrendaRevenue = ($request['product'] == "2") ? $this->analyzeModel->getProductRevenue($request->all(), 2) : 0;
                
                $fcanaliseRevenue = ($request['product'] == "3") ? $this->analyzeModel->getProductRevenue($request->all(), 3) : 0; 
                
                $fccompanyRevenue = ($request['product'] == "4") ? $this->analyzeModel->getProductRevenue($request->all(), 4) : 0;  
            }

            $solicitations = $this->solicitationModel->getSolicitationsCount($request->all());

            $totalRevenue = $fcanalisePlusRevenue + $fcanaliseRevenue + $fcrendaRevenue + $fccompanyRevenue;     
            
            $analysis = $this->analyzeModel->getAnalyzePeriodList($request->all());
         
            return view('analysis', compact('customers', 'fcanalisePlusCPFs', 
            'fcrendaCPFs', 'fcanaliseCPFs', 'fccompanyCNPJs', 'solicitations','totalRevenue', 
            'fcanalisePlusRevenue', 'fcrendaRevenue', 'fcanaliseRevenue', 'fccompanyRevenue',
            'analysis'));
        
        }else{
            
            return Excel::download(new AnalysisExport($request->all()), 'analises.xlsx');
        
        }
    }

    public function analysisRanking(AnalyzeRequest $request)
    {
        $customers = $this->personModel->getCustomers();    
        
        $analysis = $this->analyzeModel->getAnalysisRankingPeriodList($request->all());
                             
        return view('analysis-ranking', compact('customers', 'analysis'));
    }

    public function analysisCountPerMonth(Request $request)
    {
        if(!$request->input('export')){
            $analysisCount = $this->analyzeModel->getAnalysisCountPerMonth($request->all());
        }else{
            return Excel::download(new CountPerMonthExport($request->all()), 'volume-cpf-mes.xlsx');
        }
        return view('analysis-count-month', compact('analysisCount'));
    }
}
