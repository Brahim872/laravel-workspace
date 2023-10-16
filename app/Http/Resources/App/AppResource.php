<?php


namespace App\Http\Resources\App;


use App\Models\Apps;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class AppResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
