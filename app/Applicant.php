<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $table = "pretendente";

    protected $fillable = [
        'solicitation_id', 'residir', 'participante', 'tipo', 'uso_imovel',
    ];

    public function person()
    {
        return $this->hasOne('App\Person', 'id', 'id');
    }

    public function personF()
    {
        return $this->hasOne('App\PersonF', 'id', 'id');
    }

    public function personJ()
    {
        return $this->hasOne('App\PersonJ', 'id', 'id');
    }

    public function solicitation()
    {
        return $this->belongsTo('App\Solicitation', 'solicitacao_id', 'id');
    }

    public function analyze()
    {
        return $this->belongsTo('App\Analyze', 'id', 'pretendente_id');
    }
}
