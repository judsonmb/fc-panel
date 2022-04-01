<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonF extends Model
{
    protected $table = "pessoa_fisica";

    protected $fillable = [
        'cpf', 'creci', 'identidade', 'orgao_expedidor', 'data_expedicao', 
        'data_nascimento', 'sexo', 'naturalidade', 'estado_civil', 'filiacao_pai', 
        'filiacao_mae', 'filiacao_conjuge', 'pessoa_fisicable_id', 'pessoa_fisicable_type'
    ];

    public function applicant()
    {
        return $this->hasOne('App\Applicant', 'id', 'id');
    }

}
