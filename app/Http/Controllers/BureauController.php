<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bureau;
use BaoPham\DynamoDb\Facades\DynamoDb;


class BureauController extends Controller
{
    private $model;

    public function __construct()
	{
	    $this->model = new Bureau();
	}

    public function index()
    {
        return view('bureaus');
    }
    
    public function showSearchResults(Request $request)
    {
        $results = $this->model->search($request->document);
        return view('bureaus-search-results', compact('results'));
    }

    public function update(Request $request, string $document, string $bureau, string $client, string $solicitation)
    {
        $this->model->updateItem($request->all(), $document, $bureau, $client, $solicitation);
        return redirect('/bureaus')->with('status', 'Registro atualizado!');
    }

    public function delete(string $document, string $bureau, string $client, string $solicitation)
    {
        $this->model->deleteItem($document, $bureau, $client, $solicitation);
        return redirect('/bureaus')->with('status', 'Registro deletado!');
    }

    public function getDocumentsWithProcesses()
    {
        $results = $this->model->getDocumentsWithProcesses();
        return view('processes', compact('results'));
    }

    public function getDocumentsWithPendencies()
    {
        $results = $this->model->getDocumentsWithPendencies();
        return view('pendencies', compact('results'));
    }

    public function getDocumentsWithProtests()
    {
        $results = $this->model->getDocumentsWithProtests();
        return view('protests', compact('results'));
    }

    public function getDocumentsWithChecks()
    {
        $results = $this->model->getDocumentsWithChecks();
        return view('checks', compact('results'));
    }

    public function getDocumentsWithInquiries()
    {
        $results = $this->model->getDocumentsWithInquiries();
        return view('inquiries', compact('results'));
    }
}
