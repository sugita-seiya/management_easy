<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contact extends Model
{
    // public function category()
    // {
    //     return $this->belongsTo('App\Category');
    // }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    #登録日の時間のみを取得
    public function getCreatedAtAttribute($date) {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i');
    }
}

