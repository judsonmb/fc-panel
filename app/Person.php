<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Person extends Model
{
    protected $table = "pessoa";

    protected $fillable = [
        'tratamento', 'nome', 'status', 'observacao', 'pessoable_id', 
        'pessoable_type',
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'id');
    }

    public function applicant()
    {
        return $this->hasOne('App\Applicant', 'id', 'id');
    }

    public function getCustomers()
    {
        return Person::select('nome', 'id')->where('pessoable_type', '=', 'juridica')
                    ->whereNotIn('id', [1,2,322,333,348,353,3086,12402,17701,17711])
                    ->doesnthave('applicant')
                    ->orderby('nome')
                    ->get();
    }

    public function getAdmCustomers($data)
    {   
        $name = (isset($data['name'])) ? $data['name'] : '';
        $state = (isset($data['state'])) ? $data['state'] : '';

        $mesA = (date('m') == 1) ? 12 : date('m')-1;
        $anoA = (date('m') == 1) ? date('Y')-1 : date('Y');

        $results = Person::select(DB::raw('distinct(pessoa.nome)'), 'endereco.cidade', 'endereco.uf', 
        DB::raw("DATE_FORMAT(solicitacao.created_at, '%d/%m/%Y') as 'primeira_solicitacao'"),
        DB::raw("DATE_FORMAT(max(solicitacao.created_at),'%d/%m/%Y') as 'ultima_solicitacao'"),
        DB::raw("DATEDIFF(max(solicitacao.created_at), solicitacao.created_at) as 'dias_primeira_ultima'"),
        DB::raw("sum(case when month(pretendente_produto.payment_at) = $mesA 
        and year(pretendente_produto.payment_at) = $anoA then 1 else 0 end) as 'cpfs_mes_anterior'"),
        DB::raw("IF(DATEDIFF(NOW(), max(solicitacao.created_at))>90 or max(solicitacao.created_at) is null, 'INATIVO', 'ATIVO') as 'status'"))
        ->join('solicitacao', 'solicitacao.cliente_id', 'pessoa.id')
        ->join('endereco', 'endereco.endereco_id', 'pessoa.id')
        ->join('pretendente', 'pretendente.solicitacao_id', 'solicitacao.id')
        ->join('pretendente_produto', 'pretendente_produto.pretendente_id', 'pretendente.id')
        ->where('endereco.tipo', 'pessoa')
        ->where('pessoa.pessoable_type', 'juridica')
        ->where('pessoa.nome', 'like', "%".$name."%")
        ->where('endereco.uf', 'like', "%".$state."%" )
        ->groupby('pessoa.nome')
        ->orderby('pessoa.nome')
        ->get();

        if(isset($data['status'])){
            $status = $data['status'];
            $results = $results->filter(function($result) use ($status){
                return $result->status == $status;
            });
        }
        
        return $results;
    }

    public function getAdmEmployees($data)
    {
        $name = (isset($data['name'])) ? $data['name'] : '';

        return Person::select(DB::raw('distinct(pessoa.nome)'), DB::raw('p2.nome as funcionario'),
        DB::raw("IF(administradora_funcionario.master = 1,'MASTER','ADICIONAL') as 'tipo'"),
        'users.email',
        'administradora_funcionario.funcao',
        DB::raw("CONCAT(telefone.ddd, telefone.numero) as 'telefone'"))
        ->join('administradora_cliente', 'administradora_cliente.id', 'pessoa.id')
        ->join('administradora_funcionario', 'administradora_cliente.id', 'administradora_funcionario.administradora_cliente_id')
        ->leftjoin('solicitacao', 'solicitacao.solicitante_id', 'administradora_funcionario.id')
        ->join('users', 'administradora_funcionario.id', 'users.id')
        ->join(DB::raw('pessoa as p2'), 'administradora_funcionario.id', DB::raw('p2.id'))
        ->join('telefone', 'administradora_funcionario.id', 'telefone.pessoa_id')
        ->whereNotIn('pessoa.id', [1,2,322,333,348,353,12402,3086,17701,17711])
        ->where('pessoa.nome', 'like', "%".$name."%")
        ->orderby('pessoa.nome')
        ->orderby('users.email')
        ->groupby(DB::raw('p2.nome'))
        ->get();
    }

    public function getAdmEmails($data)
    {   
        $results = Person::select(DB::raw('distinct(pessoa.nome)'), DB::raw('p2.nome as funcionario'),'users.email',
        DB::raw("IF(DATEDIFF(NOW(), max(solicitacao.created_at))>90 or max(solicitacao.created_at) is null, 'INATIVO', 'ATIVO') as 'status'"))
        ->join('administradora_cliente', 'administradora_cliente.id', 'pessoa.id')
        ->join('administradora_funcionario', 'administradora_cliente.id', 'administradora_funcionario.administradora_cliente_id')
        ->leftjoin('solicitacao', 'solicitacao.solicitante_id', 'administradora_funcionario.id')
        ->join('users', 'administradora_funcionario.id', 'users.id')
        ->join(DB::raw('pessoa as p2'), 'administradora_funcionario.id', DB::raw('p2.id'))
        ->whereNotIn('pessoa.id', [1,2,322,333,348,353,12402,3086,17701,17711])
        ->orderby('pessoa.nome')
        ->orderby('users.email')
        ->groupby(DB::raw('p2.nome'))
        ->get();
        
        if(isset($data['status'])){
            $status = $data['status'];
            $results = $results->filter(function($result) use ($status){
                return $result->status == $status;
            });
        }
        
        return $results;
    }

    public function getStates()
    {
        return DB::select("select distinct(uf) from endereco where uf is not null order by uf");
    }
}
