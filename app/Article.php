<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name', 'text'];

    protected $guarded = ['id'];
}
