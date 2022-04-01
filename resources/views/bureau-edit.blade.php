@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <form action="{{ route('bureau-update', ['bcsid' => $result->bureau_client_solicitation_ids]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="input-group">
                            <textarea class="form-control" name="data">
                                {{ $result->data }}
                            </textarea>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary">Visualizar</button>
                            </div>
                        </div>                 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
