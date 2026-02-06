<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'occurrence' => ['required', 'date'],
            'description' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return Auth::user() !== null;
    }
}
