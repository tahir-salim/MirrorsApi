<?php

namespace App\Http\Controllers;

use App\Events\MessageSend;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ChatController extends Controller
{

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required',
            'message' => 'nullable|string',
            'file' => 'nullable|max:3072|mimes:png,jpg,jpeg,mp4,mp3,wav',
            'reply_id' => 'nullable',

        ]);

        if ($validator->fails()) {

            return $this->formatResponse(
                false,
                'validation-error',
                $validator->errors()->first()
            );
        }

        $user_ids = ChatUser::where('user_id', '<>', Auth::id())->where('chat_id', $request->chat_id)->get()->pluck('user_id');

        if ($user_ids->isEmpty()) {
            return $this->formatResponse(
                false,
                'chat-id-is-invalid',
                $request->chat_id
            );
        }


        $response = [];
        if ($request->has('message')) {
            $message = new Message;
            $message->user_id = Auth::id();
            $message->chat_id = $request->chat_id;
            $message->message = $request->message;
            $message->reply_id = $request->has('reply_id') ? $request->reply_id : null;
            $message->save();
            $response[] = $message;
            foreach ($user_ids as $user_id) {
                broadcast(new MessageSend($message->user, $message, $user_id));
            }
        }
        if ($request->has('file')) {
            $file = $request->file('file');
            $filePath = $file->store('messages/media', 's3');
            $messageMedia = new Message;
            $messageMedia->user_id = Auth::id();
            $messageMedia->chat_id = $request->chat_id;
            $messageMedia->media = $filePath;
            $messageMedia->is_media = Message::MEDIA;
            $messageMedia->reply_id = isset($message) ? $message->id : null;
            $messageMedia->save();
            $response[] = $messageMedia;
            foreach ($user_ids as $user_id) {
                broadcast(new MessageSend($messageMedia->user, $messageMedia, $user_id));
            }

        }

        return $this->formatResponse(
            true,
            'message-send',
            $response
        );
    }
    public function fetchChats()
    {

        $chat_ids = Message::latest()->pluck('chat_id')->unique();

        $chats = Chat::whereRelation('chatUsers', 'user_id', Auth::id())
            // ->whereHas('latestMessage')
            ->with('chatUsers', function ($query) {
                $query->where('user_id', '<>', Auth::id());
                $query->with('user');
            })
            ->with('latestMessage')
            ->orderByRaw('FIELD(id,'.$chat_ids->implode(',').')')
            ->paginate(10);


        if (!count($chats)) {

            return $this->formatResponse(
                false,
                'your-chat-is-empty',
                $chats
            );
        }

        return $this->formatResponse(
            true,
            'your-chats',
            $chats
        );
    }

    public function fetchMessages($chatID)
    {
        $chat = Chat::find($chatID);
        if (!$chat) {
            return $this->formatResponse(
                false,
                'invalid-chat-id',
                $chat
            );
        }
        $messages = $chat->messages()->orderBy('created_at', 'desc')->paginate(20);
        if ($messages->isEmpty()) {

            return $this->formatResponse(
                false,
                'this-chat-has-no-messages',
                $messages
            );
        }

          $chatUser = ChatUser::where('user_id', '<>', Auth::id())
                            ->where('chat_id', $chatID)
                            ->with('user')
                            ->first();
         $chat = [
            'messages' => $messages,
            'chatUser' => $chatUser
        ];
        return $this->formatResponse(
            true,
            'your-messages',
            $chat
        );
    }

}
