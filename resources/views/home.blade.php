@extends('layouts.app')

@section('content')
<script type="text/javascript">
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            theme: "light2",
            title:{
                text: "FC ANALISE (Média: {{ $analysisWeeklyAverage }})"
            },
            data: [{        
                type: "line",
                xValueType: "date",
                indexLabelFontSize: 10,
                dataPoints: [
                    @foreach($analysisPerDay as $analisys)
                        { x: new Date({{ $analisys->year }}, {{ $analisys->month-1 }}, {{ $analisys->day }}) , y: {{ $analisys->quantidade }} },  
                    @endforeach
                ]
            }]
        });
        chart.render();
    }
</script>
<div class="container">
    <div class="row">
        <div class="col-sm">
            <form class="form-inline" action="{{ route('home') }}" method="GET">
                @csrf
                @php
                    $end = date('Y-m-d');
                    $endDay = date('w', strtotime($end));
                    $daysToLastMonday = ($endDay != 0) ? (int) $endDay - 1 : 6;
                    $start = date('Y-m-d', strtotime(date('Y-m-d') . " - $daysToLastMonday days"));
                @endphp
                <div class="form-group mb-2">
                    <input type="date" class="form-control" name="startDate" value="{{ app('request')->input('startDate') ?? $start }}" required>
                </div>
                <div class="form-group mb-2">
                    <input type="date" class="form-control" name="endDate" value="{{ app('request')->input('endDate') ?? $end }}" required>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Filtrar</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <div class="card">
                <div class="card-header">
                    Quantidade de análises por dia (FC ANALISE)
                </div>
                <div class="card-body">
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
@endsection
