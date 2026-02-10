<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Event::class);

        return EventResource::collection(Event::where("user_id", Auth::id())->get());
    }

    public function store(EventRequest $request)
    {
        $this->authorize('create', Event::class);

        $eventDetails = $request->validated();
        $eventDetails["user_id"] = Auth::user()->id;

        return new EventResource(Event::create($eventDetails));
    }

    public function show(Event $event)
    {
        $this->authorize('view', $event);

        return new EventResource($event);
    }

    public function update(EventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $event->update($request->validated());

        return new EventResource($event);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return response()->json(null, 204);
    }

    public function restore(Event $event){
        $this->authorize('restore', $event);
        $event->restore();

        return new EventResource($event);
    }

    public function forceDestroy(Event $event)
    {
        $this->authorize('forceDelete', $event);

        if ($event->deleted_at === null) {
            return response()->json(["message"=>"Only archive event can be deleted.","errors"=>["event"=>"Only archive event can be deleted."]], 403);
        }

        $event->forceDelete();

        return response()->json(null, 204);
    }
}
