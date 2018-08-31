<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cms extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'cms';
    protected $fillable = [
        'page_name','page_title', 'keywords', 'meta_title', 'meta_description','description','status',
    ];

}
