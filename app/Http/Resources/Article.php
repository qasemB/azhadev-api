<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment as CommentResource ;

class Article extends JsonResource
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
            'category_id' => $this->category_id,
            'writer_id' => $this->writer_id,
            'writer_name' => $this->writer_name,
            'h_title' => $this->h_title,
            'top_title' => $this->top_title,
            'top_text' => $this->top_text,
            'text' => $this->text,
            'image' => $this->image,
            'alt_image' => $this->alt_image,
            'keywords' => $this->keywords,
            'last_user_view' => $this->last_user_view,
            'view_count' => $this->view_count,
            'last_user_like' => $this->last_user_like,
            'like_count' => $this->like_count,
            'is_active' => $this->is_active,
            'is_best' => $this->is_best,
            'created_at' => $this->created_at,
            'update_at' => $this->update_at,
            'comments' => CommentResource::collection($this->comments),
            'writer' => $this->writer,
            'category' => $this->category,
        ];
    }
}
