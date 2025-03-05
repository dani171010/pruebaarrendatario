<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $fillable = ['titulo', 'contenido', 'autor_id', 'imagen'];
    public $timestamps = false;

    public function autor()
    {
        return $this->belongsTo(Autor::class, 'autor_id');
    }
}
