<?php

namespace App\Livewire;

use App\Events\NewMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChatHome extends Component
{
    public $userIdActive;

    public $users;

    public $message;

    public $typing = false;

    public function mount()
    {
        $this->users = User::query()
            ->where('id', '!=', auth()->id())
            ->get();
    }

    public function setActiveUser($userId)
    {
        $this->userIdActive = $userId;
        DB::transaction(function () use ($userId) {
            Message::query()->where('from_id', $userId)
                ->where('to_id', auth()->id())
                ->chunk(10, function ($messages) {
                    foreach ($messages as $message) {
                        $message->update(['read' => true]);
                    }
                });
        });
    }

    public function sendMessage()
    {
        DB::transaction(function () {
            Message::create([
                'from_id' => auth()->id(),
                'to_id' => $this->userIdActive,
                'content' => $this->message,
                'sent_at' => now(),
            ]);
        });

        event(new NewMessage($this->message, $this->userIdActive));
        $this->message = '';
    }

    public function render()
    {
        $user = User::find($this->userIdActive);
        $messages = Message::query()->where(function ($query) {
            $query->where('from_id', auth()->id())
                ->where('to_id', $this->userIdActive);
        })->orWhere(function ($query) {
            $query->where('from_id', $this->userIdActive)
                ->where('to_id', auth()->id());
        })->orderBy('created_at', 'DESC')->get();

        return view('livewire.chat-home', compact('user', 'messages'));
    }
}
