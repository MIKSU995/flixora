<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['media_id', 'user_identifier', 'rating'];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
