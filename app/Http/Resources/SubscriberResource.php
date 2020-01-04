<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'address' => $this->address,
            'name' => $this->name,
            'show_state' => $this->show_state,            
            'state' => $this->state,
            'additional_fields' => $this->fields()->get(['field_id', 'value'])->keyBy('field_id'),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
        return parent::toArray($request);
    }
}
