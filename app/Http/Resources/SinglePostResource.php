<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SinglePostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'post_title' => $this->title,
            'post_content' => $this->content,
            'user_id' => $this->user_id,
            'published_at' => $this->created_at,
            'last_updated_at' => $this->updated_at,
            'author' => User::find($this->user_id),
            'likes_count' => Like::where('post_id', $this->id)->count(),
            'likes' => Like::where('post_id', $this->id)->get(),
            'comments_count' => Comment::where('post_id', $this->id)->count(),
            'comments' => Comment::where('post_id', $this->id)->get(),
        ];
    }
}
