<?php

namespace App\Http\Requests;

use App\Models\StravaSportType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class StoreUserGoalRequest extends FormRequest
{
    public function authorize() { return true; }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'goalTitle' => 'required',
            'goalStart' => 'required',
            'goalEnd' => 'required',
            'selectedSportTypes' => 'required'
        ];
    }

    public function name(): string
    {
        return $this->string('goalTitle');
    }

    public function start(): Carbon
    {
        return $this->date('goalStart');
    }

    public function end(): Carbon
    {
        return $this->date('goalEnd');
    }

    public function selectedSportTypes(): Collection
    {
        return StravaSportType::whereIn('group', $this->get('selectedSportTypes'))->get();
    }
}
