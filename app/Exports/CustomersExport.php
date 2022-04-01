<?php

namespace App\Exports;

use App\Person;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    private $personModel;
    private $data;

    public function __construct($data)
    { 
        $this->personModel = new Person();
        $this->data = $data;
    }
    
    public function collection()
    {
        return $this->personModel->getAdmCustomers($this->data);
    }

    public function headings() : array
    {
        return ["Imobiliária", "Cidade", "Estado", "Ativação",
         "Última solicitação", "Permanência", "CPFs mês anterior",
         "Status"];
    }
}