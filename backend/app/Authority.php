<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Authority extends Model
{
    //権限は複数のユーザーが保持する。
    public function users()
    {
        return $this->hasMany('App\User');
    }
}
