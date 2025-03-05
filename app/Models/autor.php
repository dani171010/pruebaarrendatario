<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class autor extends Model
{
    protected $table = 'autors';
    protected $primaryKey = 'id';
    protected $fillable = ['Nombre', 'Apellidos'];
    public $timestamps = false;

    public function posts()
    {
        return $this->hasMany('post', 'id');
    }
}
