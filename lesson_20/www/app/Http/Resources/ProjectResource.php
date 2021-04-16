<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Label;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $labels = Label::whereHas('projects', function($q) {
            $q->where('project_id', '=', $this->id);
        })->get();

        return [
            'id'              => $this->id,
            'project_name'    => $this->name,
            'creator_user_id' => $this->user_id,
            'labels'          => LabelResource::collection($labels)
        ];
    }
}
