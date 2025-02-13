<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_locked',
    ];

   public function chatUsers()
    {
        return $this->hasMany(ChatUser::class,'chat_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'chat_id')->latest();
    }
}
