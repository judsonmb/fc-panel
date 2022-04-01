<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Analyze extends Model
{
    protected $table = "pretendente_produto";

    protected $fillable = [
        'user_id', 'pretendente_id', 'produto_id', 'valor', 'status', 
        'analise_id','contador_edicao', 'ativo', 'payment_at',
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function applicant()
    {
        return $this->hasOne('App\Applicant', 'id', 'pretendente_id');
    }

    public function getAnalyzePeriodList($data)
    {
        $statusComparison = $data['status'] == '%' ? '!=' : 'like';
        $statusValue = $data['status'] == '%' ? '' : $data['status'];
        $productComparison = $data['product'] == '%' ? '!=' : 'like';
        $productValue = $data['product'] == '%' ? '' : $data['product'];
        $pages = (!$data['export']) ? 10 : 999999;
        
        return Analyze::whereHas('applicant', function($query) use ($data){
                            $query->whereHas('solicitation', function($query) use ($data){
                                    $query->whereHas('customer', function($query) use ($data){
                                        $query->where('id', 'LIKE', $data['customer']);
                                });
                            });
                        })
                        ->whereBetween(DB::raw('date(updated_at)'), array($data['startDate'], $data['endDate']))
                        ->where('status', $statusComparison, $statusValue)
                        ->where('produto_id', $productComparison, $productValue)
                        ->orderby('updated_at', 'desc')
                        ->with('user')
                        ->paginate($pages);
    }

    public function getProcessedDocumentsProduct($data, $product)
    {
        $r = DB::select("select count(*) as total from pretendente_produto pp 
		inner join pretendente p on p.id = pp.pretendente_id 
		inner join solicitacao s on s.id = p.solicitacao_id
        where date(pp.payment_at) BETWEEN '".$data['startDate']."' AND '".$data['endDate']."'
        and pp.produto_id = $product
        and s.cliente_id like '".$data['customer']."'
        and s.cliente_id not in (1,2,322,333,348,353,3086,12402,17701,17711)");

        return $r[0]->total;
    }

    public function getProductsRevenue($data)
    {
        $r = DB::select("select p.titulo, sum(pp.valor) as total
        from pretendente_produto pp  
        inner join produto p on p.id = pp.produto_id 
        inner join pretendente pr on pr.id = pp.pretendente_id 
        inner join solicitacao s on s.id = pr.solicitacao_id
        where date(pp.payment_at) BETWEEN '".$data['startDate']."' AND '".$data['endDate']."'
        and s.cliente_id like '".$data['customer']."'
        and s.cliente_id not in (1,2,322,333,348,353,3086,12402,17701,17711)
        group by p.titulo");

        return $r[0];
    }

    public function getProductRevenue($data, $product)
    {
        $r = DB::select("select sum(pp.valor) as total 
        from pretendente_produto pp 
        inner join pretendente p on p.id = pp.pretendente_id 
        inner join solicitacao s on s.id = p.solicitacao_id
        where date(pp.payment_at) BETWEEN '".$data['startDate']."' AND '".$data['endDate']."'
        and pp.produto_id = $product
        and s.cliente_id like '".$data['customer']."'
        and s.cliente_id not in (1,2,322,333,348,353,3086,12402,17701,17711)");

        return $r[0]->total;
    }

    public function getAnalysisRankingPeriodList($data)
    {
        return DB::table('administradora_cliente')
                    ->select('administradora_cliente.id', 'pessoa.nome', DB::raw('count(pretendente_produto.id) as total'))
                    ->join('pessoa', 'administradora_cliente.id', 'pessoa.id')
                    ->leftjoin('solicitacao', 'administradora_cliente.id', 'solicitacao.cliente_id')
                    ->leftjoin('pretendente', 'solicitacao.id', 'pretendente.solicitacao_id')
                    ->leftjoin('pretendente_produto', function($query) use ($data){
                        $query->on('pretendente.id', 'pretendente_produto.pretendente_id')
                        ->whereBetween(DB::raw('date(pretendente_produto.payment_at)'), array($data['startDate'], $data['endDate']))
                        ->where('pretendente_produto.status', '!=', 'INCLUIDO');
                    })
                    ->whereNotIn('solicitacao.cliente_id', [1,2,322,333,348,353,3086,12402,17701,17711])
                    ->groupBy('administradora_cliente.id', 'pessoa.nome')
                    ->orderBy(DB::raw('count(pretendente_produto.id)'), 'desc')
                    ->orderby('pessoa.nome')
                    ->paginate(10);                 
    }

    public function getAnalysisCountPerMonth($data)
    {
        $year = (isset($data['year'])) ? $data['year'] : date('Y');

        return DB::select("SELECT 
        p.nome as cliente,
        e.uf,
        IF(DATEDIFF(NOW(), max(s.created_at))>90, 'INATIVO', 'ATIVO') as 'status',
        sum(case when year(pp.payment_at) = $year then 1 else 0 end) as total,
        sum(case when month(pp.payment_at) = 1 and year(pp.payment_at) = $year then 1 else 0 end) as janeiro,
        sum(case when month(pp.payment_at) = 2 and year(pp.payment_at) = $year then 1 else 0 end) as fevereiro,
        sum(case when month(pp.payment_at) = 3 and year(pp.payment_at) = $year then 1 else 0 end) as março,
        sum(case when month(pp.payment_at) = 4 and year(pp.payment_at) = $year then 1 else 0 end) as abril,
        sum(case when month(pp.payment_at) = 5 and year(pp.payment_at) = $year then 1 else 0 end) as maio,
        sum(case when month(pp.payment_at) = 6 and year(pp.payment_at) = $year then 1 else 0 end) as junho,
        sum(case when month(pp.payment_at) = 7 and year(pp.payment_at) = $year then 1 else 0 end) as julho,
        sum(case when month(pp.payment_at) = 8 and year(pp.payment_at) = $year then 1 else 0 end) as agosto,
        sum(case when month(pp.payment_at) = 9 and year(pp.payment_at) = $year then 1 else 0 end) as setembro,
        sum(case when month(pp.payment_at) = 10 and year(pp.payment_at) = $year then 1 else 0 end) as outubro,
        sum(case when month(pp.payment_at) = 11 and year(pp.payment_at) = $year then 1 else 0 end) as novembro,
        sum(case when month(pp.payment_at) = 12 and year(pp.payment_at) = $year then 1 else 0 end) as dezembro
        from 
            solicitacao s 
        inner join
            pessoa p on p.id = s.cliente_id 
        inner join 
            pretendente pr on pr.solicitacao_id = s.id 
        inner join 
            pretendente_produto pp on pp.pretendente_id = pr.id
        inner join
            endereco e on e.endereco_id = p.id
        where
            p.pessoable_type = 'juridica'
        and
            s.cliente_id not in (1,2,322,333,348,353,3086,12402,17701,17711) 
        and e.tipo = 'pessoa'
        group by 
            p.nome");
    }

    public function getAnalysisWeeklyAverage($data)
    {
        $start = $data['startDate'];
        $end = $data['endDate'];
        $diff = ((strtotime($end)-strtotime($start))/86400)+1;
        $q = $diff;
        for($i=0;$i<$diff;$i++){
            $s = date('Y-m-d', strtotime($start . " + $i days"));
            $s = date('w', strtotime($s));
            if($s == 6 || $s == 0){
                $q--;
            }
        }
        $end = date('Y-m-d', strtotime($end . ' + 1 days'));
        return DB::select("SELECT format(count(*)/$q,0) AS media
        FROM   pretendente_produto pp 
        INNER JOIN pretendente p ON p.id = pp.pretendente_id
        INNER JOIN solicitacao s ON s.id = p.solicitacao_id
        WHERE date(payment_at) BETWEEN '$start' AND '$end'
        AND s.cliente_id not in (1,2,322,333,348,353,3086,12402,17701,17711)
        AND payment_at is not null")[0]->media;
    }

    public function getLegacyAnalysisWeeklyAverage($data)
    {
        $start = $data['startDate'];
        $end = $data['endDate'];
        $diff = ((strtotime($end)-strtotime($start))/86400)+1;
        $q = $diff;
        for($i=0;$i<$diff;$i++){
            $s = date('Y-m-d', strtotime($start . " + $i days"));
            $s = date('w', strtotime($s));
            if($s == 6 || $s == 0){
                $q--;
            }
        }
        $end = date('Y-m-d', strtotime($end . ' + 1 days'));
        return DB::connection('legado')
                ->select("SELECT format(count(*)/$q,0) AS media 
                            FROM fc_ficha 
                            WHERE date(data_solicitacao) BETWEEN '$start' 
                            AND '$end'
                            AND status = 'L'")[0]->media;
    }

    public function getAnalysisPerDay($data)
    {
        $end = date('Y-m-d', strtotime($data['endDate'] . ' + 1 days'));
        return DB::select("SELECT year(payment_at) as year, month(payment_at) as month, day(payment_at) as day, count(*) as quantidade
        FROM   pretendente_produto pp 
        INNER JOIN pretendente p ON p.id = pp.pretendente_id
        INNER JOIN solicitacao s ON s.id = p.solicitacao_id
        WHERE date(payment_at) BETWEEN '".$data['startDate']."' AND '$end'
        AND s.cliente_id not in (1,2,322,333,348,353,3086,12402,17701,17711)
        AND payment_at is not null
        GROUP BY date(payment_at)");
    }

    public function getLegacyAnalysisPerDay($data)
    {
        $end = date('Y-m-d', strtotime($data['endDate'] . ' + 1 days'));
        return DB::connection('legado')
        ->select("select year(data_solicitacao) as year, 
        month(data_solicitacao) as month, 
        day(data_solicitacao) as day, 
        count(*) as quantidade 
        FROM fc_ficha
        WHERE date(data_solicitacao) BETWEEN '".$data['startDate']."' AND '$end' 
        AND pid IN (1,2,3,4,5,34,35)
        GROUP BY date(data_solicitacao)");
    }
}
