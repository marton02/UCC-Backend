<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HelpdeskTicketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subject' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
