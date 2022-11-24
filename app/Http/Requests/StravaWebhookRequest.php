<?php

namespace App\Http\Requests;

use App\Enums\StravaWebhooks\StravaAspectTypeEnum;
use App\Enums\StravaWebhooks\StravaObjectTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class StravaWebhookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function aspectType(): StravaAspectTypeEnum
    {
        return StravaAspectTypeEnum::from($this->get('aspect_type'));
    }

    public function objectType(): StravaObjectTypeEnum
    {
        return StravaObjectTypeEnum::from($this->get('object_type'));
    }

    public function athleteId(): int
    {
        return (int) $this->get('owner_id');
    }

    public function objectId(): int
    {
        return (int) $this->get('object_type');
    }

    public function updatesArray(): array
    {
        return $this->get('updates');
    }
}
