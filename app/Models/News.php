<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'numLikes',
        'date',
        'urlPhoto',
        'created_at',
        'updated_at',
        'urlNews',
    ];

    protected $table = 'news';
    protected $primaryKey = 'idNews';

   

    public function userSaved() {

        return $this->belongsToMany('App\Models\News','user_news','idNews', 'idUser');
    }
}
