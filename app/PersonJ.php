<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonJ extends Model
{
    protected $table = "pessoa_juridica";

    protected $fillable = [
        'cnpj', 'nome_fantasia', 'tempo_fundacao', 'creci', 'pessoa_juridicable_id', 
        'pessoa_juridicable_type'
    ];

    public function applicant()
    {
        return $this->hasOne('App\Applicant', 'id', 'id');
    }

}
