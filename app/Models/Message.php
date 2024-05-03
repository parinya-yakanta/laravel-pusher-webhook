<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'from_id',
        'to_id',
        'content',
        'read',
        'read_at',
        'sent_at',
        'received_at',
        'deleted_at',
    ];

    public function from()
    {
        return $this->belongsTo(User::class, 'from_id', 'id');
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'to_id', 'id');
    }
}
