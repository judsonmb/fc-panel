<?php

namespace App\Exports;

use App\Analyze;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnalysisExport implements FromCollection, WithHeadings, WithMapping
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
        return $this->analyzeModel->getAnalyzePeriodList($this->data);
    }

    public function map($analyze) : array
    {
        $payment = ($analyze->payment_at == null) ? 'NÃO' : 'SIM';
        $document = isset($analyze->applicant->personF) ? $analyze->applicant->personF->cpf 
                                                        : $analyze->applicant->personJ->cnpj;
        return [
            $analyze->applicant->solicitacao_id,
            $analyze->user->person->nome,
            $analyze->applicant->solicitation->customer->nome,
            $document,
            $analyze->applicant->person->nome,
            $analyze->status,
            number_format($analyze->valor, 2, ",", "."),
            $analyze->created_at->format("d/m/Y H:i:s"),
            $payment
        ];
    }

    public function headings() : array
    {
        return ["ID", "Usuário", "Cliente", "CPF/CNPJ pret.", "Nome pret", "Status", "Valor","Data/Hora", "Cobrança"];
    }
}