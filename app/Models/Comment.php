<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['media_id', 'user_name', 'comment_text'];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
