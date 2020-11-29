<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Work_section extends Model
{
    public function works()
    {
        return $this->hasMany('App\Work');
    }
}
