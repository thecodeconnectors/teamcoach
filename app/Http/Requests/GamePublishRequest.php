<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GamePublishRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('game'));
    }
}
