<?php

namespace App\Exports;

use App\Person;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmailsExport implements FromCollection, WithHeadings
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
        return $this->personModel->getAdmEmails($this->data);
    }

    public function headings() : array
    {
        return ["Imobiliária", "Funcionário", "E-mail", "Status"];
    }
}