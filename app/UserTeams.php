<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTeams extends Model
{
        //Table name
    protected $table = "userteam";

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'teams_id', 'user_id',
    ];



    public function team()
    {
        return $this->hasMany('App\Teams', 'id', 'teams_id');
    }
}
