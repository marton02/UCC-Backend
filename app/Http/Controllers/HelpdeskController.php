<?php

namespace App\Http\Controllers;

use App\Http\Resources\HelpdeskTicketResource;
use Illuminate\Support\Facades\Auth;

class HelpdeskController extends Controller
{
    public function index()
    {
        return new HelpdeskTicketResource(Auth::user()->tickets()->orderByDesc("created_at")->get());
    }
}
