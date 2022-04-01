@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <form action="{{ route('analysis') }}" method="GET">
                        @csrf
                        <div class="input-group">
                            <input type="date" class="form-control" name="startDate" value="{{ app('request')->input('startDate') ?? date('Y-m-d') }}" required>
                            <input type="date" class="form-control" name="endDate" value="{{ app('request')->input('endDate') ?? date('Y-m-d') }}" required>
                            <select class="form-control" name="customer" required>
                                <option value="%">CLIENTES</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ (app("request")->input("customer") == $customer->id ? "selected":"") }}>{{ $customer->nome }}</option>
                                @endforeach
                            </select>
                            <select class="form-control" name="product" required>
                                <option value="%">PRODUTOS</option>
                                <option value="1" {{ (app("request")->input("product") == '1' ? "selected":"") }}>FC ANALISE+</option>
                                <option value="3" {{ (app("request")->input("product") == '3' ? "selected":"") }}>FC ANALISE</option>
                                <option value="4" {{ (app("request")->input("product") == '4' ? "selected":"") }}>FC EMPRESA</option>
                                <option value="2" {{ (app("request")->input("product") == '2' ? "selected":"") }}>FC RENDA</option>
                            </select>
                            <select class="form-control" name="status" required>
                                <option value="%">STATUS (sem os INCLUIDOS)</option>
                                <option value="CONCLUIDO" {{ (app("request")->input("status") == 'CONCLUIDO' ? "selected":"") }}>FINALIZADO</option>
                                <option value="SOLICITADO" {{ (app("request")->input("status") == 'SOLICITADO' ? "selected":"") }}>SOLICITADO</option>
                                <option value="INCLUIDO" {{ (app("request")->input("status") == 'INCLUIDO' ? "selected":"") }}>INCLUIDO</option>
                                <option value="EDITADO" {{ (app("request")->input("status") == 'EDITADO' ? "selected":"") }}>EDITADO</option>
                                <option value="REINCLUIDO" {{ (app("request")->input("status") == 'REINCLUIDO' ? "selected":"") }}>REINCLUIDO</option>
                                <option value="ANDAMENTO" {{ (app("request")->input("status") == 'ANDAMENTO' ? "selected":"") }}>ANDAMENTO</option>
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
        @if(isset($analysis) && $analysis->count() > 0)
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        Locações: {{ $solicitations }}
                    </div>
                    <div class="col">
                        Qtd FC ANALISE<sup>+</sup>: {{ $fcanalisePlusCPFs }}
                    </div>
                    <div class="col">
                        Qtd FC ANALISE: {{ $fcanaliseCPFs }}
                    </div>
                    <div class="col">
                        Qtd FC RENDA: {{ $fcrendaCPFs }}
                    </div>
                    <div class="col">
                        Qtd FC EMPRESA: {{ $fccompanyCNPJs }}
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Receita total: R$ {{ number_format($totalRevenue, 2, ",", ".") }}
                    </div>
                    <div class="col">
                        Receita FC ANALISE<sup>+</sup>: R$ {{ number_format($fcanalisePlusRevenue, 2, ",", ".") }}
                    </div>
                    <div class="col">
                        Receita FC ANALISE: R$ {{ number_format($fcanaliseRevenue, 2, ",", ".") }}
                    </div>
                    <div class="col">
                        Receita FC RENDA: R$ {{ number_format($fcrendaRevenue, 2, ",", ".") }}
                    </div>
                    <div class="col">
                        Receita FC EMPRESA: R$ {{ number_format($fccompanyRevenue, 2, ",", ".") }}
                    </div>
                    
            </div>
            <div class="card-body" style="max-width: 100%;overflow: scroll;">
                <table class="table table-striped" >
                    <thead>
                        <tr>
                            <th scope="col">ID da solicitação</th>
                            <th scope="col">Usuário</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">CPF/CNPJ do pretendente</th>
                            <th scope="col">Nome do pretendente</th>
                            <th scope="col">Status do processamento</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Data e hora</th>
                            <th scope="col">Houve cobrança?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($analysis as $analyze)
                            <tr>
                                <td scope="col">{{ $analyze->applicant->solicitacao_id ?? '-' }}</td>
                                <td scope="col">{{ $analyze->user->person->nome }}</td>
                                <td scope="col">{{ $analyze->applicant->solicitation->customer->nome ?? '-' }}</td>
                                <td scope="col">@if($analyze->applicant->personF) {{ $analyze->applicant->personF->cpf }} @else {{ $analyze->applicant->personJ->cnpj }} @endif</td>
                                <td scope="col">{{ $analyze->applicant->person->nome }}</td>
                                <td scope="col">{{ $analyze->status }}</td>
                                <td scope="col">R$ {{ number_format($analyze->valor, 2, ",", ".") }}</td>
                                <td scope="col">{{ $analyze->created_at->format("d/m/Y H:i:s") }}</td>
                                <td scope="col">@if($analyze->payment_at == null) {{ 'NÃO' }} @else {{ 'SIM' }} @endif</td>
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
