<?php

namespace App\Exports;

use App\Analyze;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class CountPerMonthExport implements FromCollection, WithHeadings
{
    use Exportable;
    
    private $analyzeModel;
    private $data;

    public function __construct($data)
    { 
        $this->analyzeModel = new Analyze();
        $this->data = $data;
    }
    
    public function collection()
    {
        return collect($this->analyzeModel->getAnalysisCountPerMonth($this->data));
    }

    public function map($month) : array
    {
        return [
            $month->total,
            $month->uf,
            $month->status,
            $month->janeiro,
            $month->fevereiro,
            $month->marco,
            $month->abril,
            $month->junho,
            $month->julho,
            $month->agosto,
            $month->setembro,
            $month->outubro,
            $month->novembro,
            $month->dezembro,
        ];
    }

    public function headings() : array
    {
        return ["Nome do Cliente", "uf", "Status", "Total", "Jan", 
        "Fev", "Mar", "Abr", "Mai","Jun", "Jul", "Ago", 
        "Set", "Out", "Nov", "Dez"];    
    }
}