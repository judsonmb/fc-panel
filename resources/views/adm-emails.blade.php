@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <form action="{{ route('adm-emails') }}" method="GET">
                        @csrf
                        <div class="input-group">
                            <select class="form-control" name="status">
                                <option value="">ATIVOS E INATIVOS</option>
                                <option value="ATIVO" {{ (app("request")->input("status") == 'ATIVO' ? "selected":"") }}>ATIVOS</option>
                                <option value="INATIVO" {{ (app("request")->input("status") == 'INATIVO' ? "selected":"") }}>INATIVOS</option>
                            </select>
                            <select class="form-control" name="export">
                                <option value="0">NÃO DESEJO EXPORTAR</option>
                                <option value="1">DESEJO EXPORTAR PARA EXCEL</option>
                            </select>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </div>                 
                    </form>
                </div>
            </div>
        </div>
        @if(isset($emails) && count($emails) > 0)
            <div class="card-body" style="max-width: 100%;overflow: scroll;">
                <table class="table table-striped" >
                    <thead>
                        <tr>
                            <th scope="col">Imobiliária</th>
                            <th scope="col">Nome do funcionário</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Status</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($emails as $customer)
                            <tr>
                                <td scope="col">{{ $customer->nome }}</td>
                                <td scope="col">{{ $customer->funcionario }}</td>
                                <td scope="col">{{ $customer->email }}</td>
                                <td scope="col">{{ $customer->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>          
            </div>
        @else
            <p style="text-align:center;">Nenhum resultado encontrado.</p>
        @endif

    </div>
</div>
@endsection
