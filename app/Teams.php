<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    //Table name
    protected $table = "teams";

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

}
