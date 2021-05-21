<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketScanRequest extends FormRequest
{
    public function rules()
    {
        return [
            'ticket_uuid' => 'required|uuid',
            'pool' => 'required|in:delfin,rosnicka,zlate_piesky,tehelne_pole',
        ];
    }

    public function messages()
    {
        return  [
            'pool.in' => 'Must be one of: :values'
        ];
    }
}
