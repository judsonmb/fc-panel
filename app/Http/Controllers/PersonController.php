<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\Http\Requests\AdmCustomerRequest;
use App\Http\Requests\AdmEmployeeRequest;
use App\Http\Requests\CustomersEmailsRequest;
use App\Exports\CustomersExport;
use App\Exports\EmployeesExport;
use App\Exports\EmailsExport;
use Maatwebsite\Excel\Facades\Excel;


class PersonController extends Controller
{
    private $personModel;

    public function __construct()
    { 
        $this->personModel = new Person();
    }

    public function getAdmCustomers(AdmCustomerRequest $request)
    {
        if(!$request->input('export')){
            $states = $this->personModel->getStates();
            $customers = $this->personModel->getAdmCustomers($request->all());
            return view('adm-customers', compact('customers', 'states'));
        }else{
            return Excel::download(new CustomersExport($request->all()), 'imobiliarias.xlsx');
        }
    }

    public function getAdmEmployees(AdmEmployeeRequest $request){
        if(!$request->input('export')){
            $employees = $this->personModel->getAdmEmployees($request->all());
            return view('adm-employees', compact('employees'));
        }else{
            return Excel::download(new EmployeesExport($request->all()), 'funcionarios.xlsx');
        }
        
    }

    public function getAdmEmails(Request $request){
        if(!$request->input('export')){
            $emails = $this->personModel->getAdmEmails($request->all());
            return view('adm-emails', compact('emails'));
        }else{
            return Excel::download(new EmailsExport($request->all()), 'emails.xlsx');
        }
        
    }
}
