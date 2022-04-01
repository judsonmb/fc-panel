@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <form action="{{ route('analysis-count-month') }}" method="GET">
                        @csrf
                        <div class="input-group">
                            <input type="number" class="form-control" name="year" value="{{ app('request')->input('year') ?? date('Y') }}" required>
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
        <div class="card-body" style="max-width: 100%;overflow: scroll;">
            <table class="table table-striped" >
                <thead>
                    <tr>
                        <th scope="col">Nome do Cliente</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Status</th>
                        <th scope="col">Total</th>
                        <th scope="col">Jan</th>
                        <th scope="col">Fev</th>
                        <th scope="col">Mar</th>
                        <th scope="col">Abr</th>
                        <th scope="col">Mai</th>
                        <th scope="col">Jun</th>
                        <th scope="col">Jul</th>
                        <th scope="col">Ago</th>
                        <th scope="col">Set</th>
                        <th scope="col">Out</th>
                        <th scope="col">Nov</th>
                        <th scope="col">Dez</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($analysisCount as $ac)
                        <tr>
                            <td scope="col">{{ $ac->cliente }}</td>
                            <td scope="col">{{ $ac->uf }}</td>
                            <td scope="col">{{ $ac->status }}</td>
                            <td scope="col">{{ $ac->total }}</td>
                            <td scope="col">{{ $ac->janeiro }}</td>
                            <td scope="col">{{ $ac->fevereiro }}</td>
                            <td scope="col">{{ $ac->março }}</td>
                            <td scope="col">{{ $ac->abril }}</td>
                            <td scope="col">{{ $ac->maio }}</td>
                            <td scope="col">{{ $ac->junho }}</td>
                            <td scope="col">{{ $ac->julho }}</td>
                            <td scope="col">{{ $ac->agosto }}</td>
                            <td scope="col">{{ $ac->setembro }}</td>
                            <td scope="col">{{ $ac->outubro }}</td>
                            <td scope="col">{{ $ac->novembro }}</td>
                            <td scope="col">{{ $ac->dezembro }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>        
        </div>
    </div>
</div>
@endsection
