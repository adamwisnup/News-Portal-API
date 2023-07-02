<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => 200,
            'message' => 'OK',
            'data' => [
                'id' => $this->id,
                'title' => $this->title,
                'news_content' => $this->news_content,
                'created_at' => date_format($this->created_at, 'Y/m/d H:i:s'),
            ],
        ];
    }
}
