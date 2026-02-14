<?php

namespace App\Http\Controllers;

use App\Http\Requests\HelpdeskMessageRequest;
use App\Http\Requests\HelpdeskTicketRequest;
use App\Http\Resources\HelpdeskMessageResource;
use App\Http\Resources\HelpdeskTicketResource;
use App\Jobs\SearchFaqForHelpdeskMessage;
use App\Models\HelpdeskMessage;
use App\Models\HelpdeskTicket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HelpdeskController extends Controller
{
    public function index()
    {
        return HelpdeskTicketResource::collection(
            Auth::user()
                ->tickets()
                ->with(['messages.user'])
                ->orderByDesc("created_at")
                ->get());
    }

    public function postMessage(HelpdeskTicket $ticket, HelpdeskMessageRequest $request){
        try{
            $message = DB::transaction(function () use ($ticket, $request) {

                if ($ticket->status === "closed") {
                    $ticket->status = "open";
                    $ticket->save();
                }

                $message = new HelpdeskMessage();
                $message->user_id = Auth::user()->id;
                $message->content = $request->get('content');
                $message->helpdesk_ticket_id = $ticket->id;
                $message->save();

                return $message;
            });
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return response()->json(["error"=>$exception->getMessage()],500);
        }

        if ($ticket->status === 'open' && is_null($ticket->agent_id)) {
            SearchFaqForHelpdeskMessage::dispatch($message->id)->afterCommit();
        }

        $message->load('user');
        return new HelpdeskMessageResource($message);
    }

    public function createTicket(HelpdeskTicketRequest $request){
        try{
            $ticket = HelpdeskTicket::create([
                "user_id" => Auth::user()->id,
                "subject" => $request->get('subject'),
                "status" => "open",
            ]);
            return new HelpdeskTicketResource($ticket);
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return response()->json(["error"=>$exception->getMessage()],500);
        }
    }
}
