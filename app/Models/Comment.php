<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comments';

    protected $fillable = [
        'post_id', 'user_id', 'comment_content'
    ];

    public function commentator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
