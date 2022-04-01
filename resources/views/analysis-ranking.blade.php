@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <form action="{{ route('analysis-ranking') }}" method="GET">
                        @csrf
                        <div class="input-group">
                            <input type="date" class="form-control" name="startDate" value="{{ app('request')->input('startDate') ?? date('Y-m-d') }}" required>
                            <input type="date" class="form-control" name="endDate" value="{{ app('request')->input('endDate') ?? date('Y-m-d') }}" required>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </div>                 
                    </form>
                </div>
            </div>
        </div>
        @if(isset($analysis) && $analysis->count() > 0)
            <div class="card-body" style="max-width: 100%;overflow: scroll;">
                <table class="table table-striped" >
                    <thead>
                        <tr>
                            <th scope="col">ID do Cliente</th>
                            <th scope="col">Nome do Cliente</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($analysis as $analyze)
                            <tr>
                                <td scope="col">{{ $analyze->id }}</td>
                                <td scope="col">{{ $analyze->nome }}</td>
                                <td scope="col">{{ $analyze->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer" style="max-width: 100%;overflow: scroll;">
                    {{ $analysis->appends(request()->input())->links() }}
                </div>            
            </div>
        @else
            <p style="text-align:center;">Nenhum resultado encontrado.</p>
        @endif
    </div>
</div>
@endsection
