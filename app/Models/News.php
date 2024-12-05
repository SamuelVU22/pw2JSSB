<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'idNews',
        'title',
        'content',
        'numLikes',
        'imageUrl',
        'created_at',
        'updated_at',
    ];

    protected $table = 'news';
    protected $primaryKey = 'idNews';

   

    public function userSaved() {

        return $this->belongsToMany('App\Models\News','usernews','idNews', 'idUser');
    }
}
