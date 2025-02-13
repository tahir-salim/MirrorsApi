<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    const MEDIA = 1;
    const NOMEDIA = 0;

    public function chat()
    {
        return $this->belongsTo(Chat::class,'chat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function chatUser()
    {
        return $this->hasMany(ChatUser::class, 'chat_id', 'chat_id');
    }
}
