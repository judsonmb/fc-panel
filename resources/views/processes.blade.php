@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        @if(count($results) > 0)
            <div class="card-header">
                Pretendentes com Processos (limitado a apenas 10 resultados) de {{ date('Y') }} e {{ date('Y')-1 }}
            </div>
            <div class="card-body" style="max-width: 100%;overflow: scroll;">
                <table class="table table-striped" >
                    <thead>
                        <tr>
                            <th scope="col">Solicitação</th>
                            <th scope="col">Documento</th>
                            <th scope="col">Data de registro</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $result)
                            <tr>
                                @php    
                                    $ids = explode('#', $result['bureau_client_solicitation_ids']['S']);
                                @endphp
                                <td scope="col">{{ $ids[2] }}</td>
                                <td scope="col">{{ $result['document']['S'] }}</td>
                                <td scope="col">{{ $result['dated']['S'] }}</td>
                                <td scope="col">
                                   
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#{{$ids[0]}}-{{$ids[1]}}-{{$ids[2]}}">Visualizar</button>                              
                                    <div class="modal fade" id="{{$ids[0]}}-{{$ids[1]}}-{{$ids[2]}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="dataModalLabel">{{$result['document']['S']}} / {{ $result['bureau_client_solicitation_ids']['S'] }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('bureau-update', ['document' => $result['document']['S'], 'bureau' => $ids[0], 'client' => $ids[1], 'solicitation' => $ids[2]]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <textarea cols="55" rows="20" name="data">
                                                            {{ json_encode($result['data']['M']) }}
                                                        </textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div> 
                                    <form action="{{ route('bureau-delete', ['document' => $result['document']['S'], 'bureau' => $ids[0], 'client' => $ids[1], 'solicitation' => $ids[2] ]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Deletar</button>
                                    </form>
                                </td>
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
