<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleTranslation extends Model

{
    protected $table = 'article_translations';//me
    public $timestamps = false;

    protected $guarded = ['id'];
}
