<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserStravaDescriptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function enabled(): bool
    {
        return $this->boolean('enabled');
    }

    public function simple(): bool
    {
        return $this->boolean('simple');
    }

    public function showTotals(): bool
    {
        return $this->boolean('showTotals');
    }

    public function showWeekStats(): bool
    {
        return $this->boolean('showWeekStats');
    }

    public function showMonthStats(): bool
    {
        return $this->boolean('showMonthStats');
    }
}
