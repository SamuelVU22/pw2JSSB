<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photos extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'numLikes',
        'date',
        'urlPhoto',
        
    ];

    protected $table = 'photos';
    protected $primaryKey = 'idPhoto';

   

    public function userSaved() {

        return $this->belongsToMany('App\Models\Photos','user_photos','idPhoto', 'idUser');
    }
}

