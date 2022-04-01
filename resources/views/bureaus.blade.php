@extends('layouts.app')

@section('content')
<div class="container">
    @if(Session::has('status'))        
        <div class="alert alert-success" role="alert">
            {{Session::get('status')}}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <form action="{{ route('bureaus-search-results') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input placeholder='Número do documento, sem pontos e barras' type="text" class="form-control" name="document" value="{{ app('request')->input('document') ?? '' }}" required>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </div>
                        </div>                 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
