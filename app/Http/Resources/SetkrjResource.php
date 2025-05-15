<?php

namespace App\Http\Resources;

use App\Models\SetkrjModel;
use Illuminate\Http\Resources\Json\JsonResource;

class SetkrjResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function index()
    {
        $Setkrj = SetkrjModel::all();
        return response()->json($Setkrj);
    }
}
