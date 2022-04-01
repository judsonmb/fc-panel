@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <form action="{{ route('adm-customers') }}" method="GET">
                        @csrf
                        <div class="input-group">
                            <input type="text" placeholder="filtre por nome" class="form-control" name="name" value="{{ app('request')->input('name') ?? '' }}">
                            <select class="form-control" name="state">
                                <option value="">TODOS OS ESTADOS</option>
                                @foreach($states as $state)
                                   <option value="{{ $state->uf }}" {{ (app("request")->input("state") == $state->uf ? "selected":"") }}>{{ $state->uf }}</option>
                                @endforeach
                            </select>
                            <select class="form-control" name="status">
                                <option value="">ATIVOS E INATIVOS</option>
                                <option value="ATIVO" {{ (app("request")->input("status") == 'ATIVO' ? "selected":"") }}>ATIVOS</option>
                                <option value="INATIVO" {{ (app("request")->input("status") == 'INATIVO' ? "selected":"") }}>INATIVOS</option>
                            </select>
                            <select class="form-control" name="export">
                                <option value="0">NÃO DESEJO EXPORTAR</option>
                                <option value="1">DESEJO EXPORTAR PARA EXCEL</option>
                            </select>
                        </div>  
                        <button type="submit" class="btn btn-primary">Filtrar</button>               
                    </form>    
                </div>
            </div>
        </div>
        @if(isset($customers) && count($customers) > 0)
            <div class="card-body" style="max-width: 100%;overflow: scroll;">
                <table class="table table-striped" >
                    <thead>
                        <tr>
                            <th scope="col">Imobiliária</th>
                            <th scope="col">Cidade</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Ativação</th>
                            <th scope="col">Última solicitação</th>
                            <th scope="col">Permanência</th>
                            <th scope="col">CPFs mês anterior</th>
                            <th scope="col">Status</th>   
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr @if($customer->status == 'INATIVO') style="background-color:#ee5654;" @endif>
                                <td scope="col" title="{{ $customer->nome }}">{{ substr($customer->nome, 0, 15) }}...</td>
                                <td scope="col">{{ $customer->cidade }}</td>
                                <td scope="col">{{ $customer->uf }}</td>
                                <td scope="col">{{ $customer->primeira_solicitacao }}</td>
                                <td scope="col">{{ $customer->ultima_solicitacao }}</td>
                                <td scope="col">{{ $customer->dias_primeira_ultima }} dias</td>
                                <td scope="col">{{ $customer->cpfs_mes_anterior }}</td>
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
