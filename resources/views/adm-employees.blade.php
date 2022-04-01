@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
             <div class="row">
                <div class="col">
                    <form action="{{ route('adm-employees') }}" method="GET">
                        @csrf
                        <div class="input-group">
                            <input type="text" placeholder="filtre por nome" class="form-control" name="name" value="{{ app('request')->input('name') ?? '' }}">
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
        @if(isset($employees) && count($employees) > 0)
            <div class="card-body" style="max-width: 100%;overflow: scroll;">
                <table class="table table-striped" >
                    <thead>
                        <tr>
                            <th scope="col">Imobiliária</th>
                            <th scope="col">Funcionário</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Usuário</th>
                            <th scope="col">Função</th>
                            <th scope="col">Telefone</th>  
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td scope="col" title="{{ $employee->nome }}">{{ substr($employee->nome, 0, 15) }}...</td>
                                <td scope="col" title="{{ $employee->funcionario }}">{{ substr($employee->funcionario, 0, 15) }}...</td>
                                <td scope="col">{{ $employee->tipo }}</td>
                                <td scope="col">{{ $employee->email }}</td>
                                <td scope="col">{{ $employee->funcao }}</td>
                                <td scope="col">{{ $employee->telefone }}</td>
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
