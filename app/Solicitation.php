<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Solicitation extends Model
{
    protected $table = "solicitacao";

    protected $fillable = [
        'id_fc', 'solicitante_id', 'cliente_id', 'status', 'carrinho', 
        'contador_edicao','notified_at',
    ];

    public function customer()
    {
        return $this->hasOne('App\Person', 'id', 'cliente_id');
    }

    public function applicant()
    {
        return $this->hasMany('App\Applicant', 'solicitacao_id', 'id');
    }

    public function getSolicitationsCount($data)
    {   
        $n = DB::select("select count(distinct(s.id)) as solicitacoes from pretendente_produto pp
        inner join pretendente p on p.id = pp.pretendente_id
        inner join solicitacao s on s.id = p.solicitacao_id
        where pp.produto_id like '".$data['product']."'
        and date(pp.payment_at) between '".$data['startDate']."' and '".$data['endDate']."'
        and s.cliente_id not in (1,2,322,333,348,353,3086,12402,17701,17711)
        and s.cliente_id like '".$data['customer']."'");
        
        return $n[0]->solicitacoes;
    }
}
