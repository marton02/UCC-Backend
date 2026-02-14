<?php

namespace App\Jobs;

use App\Models\Faq;
use App\Models\HelpdeskMessage;
use App\Events\NewMessageEvent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SearchFaqForHelpdeskMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $messageId) {}

    public function handle(): void
    {
        $message = HelpdeskMessage::with('helpdeskTicket')->find($this->messageId);
        if (!$message || !$message->helpdeskTicket) return;

        $meilisearchUser = User::where("name","Meilisearch")->first();

        $ticket = $message->helpdeskTicket;

        if ($ticket->status !== 'open' || !is_null($ticket->agent_id)) {
            return;
        }

        $query = trim((string) $message->content);
        if ($query === '') return;

        $faq = Faq::search($query)
            ->take(1)
            ->get();

        $raw = Faq::search($query)->take(1)->raw();

        if ($faq->isEmpty()) {
            $meiliAnswer = HelpdeskMessage::create([
                "helpdesk_ticket_id" => $ticket->id,
                "user_id" => $meilisearchUser->id,
                "content" => "Sajnos nem találtam a kérdésére választ az adatbázisunkban. Kérem próbálja meg másként megismételni, vagy kérje ügyfélszolgálatunk segítségét.",
            ]);
        }else{
            $meiliAnswer = HelpdeskMessage::create([
                "helpdesk_ticket_id" => $ticket->id,
                "user_id" => $meilisearchUser->id,
                "content" => $faq->first()->answer,
            ]);
        }

        event(new NewMessageEvent($meiliAnswer));
    }
}
