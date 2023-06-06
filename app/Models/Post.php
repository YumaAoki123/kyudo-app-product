<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_id',
        'pointX',
        'pointY',
        'pointNumber',
        'shotCount'
    ];


    public function date()
    {
        return $this->belongsTo(Date::class);
    }
}
