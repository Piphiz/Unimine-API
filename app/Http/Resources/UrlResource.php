<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'link' => $this->link,
            'hash' => $this->hash,
            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('d-m-Y H:i:s') : null,
            'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->format('d-m-Y H:i:s') : null,
        ];
    }
}
