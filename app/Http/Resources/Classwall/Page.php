<?php

namespace App\Http\Resources\Classwall;

use App\Models\ClassRoomPageDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Page extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            //
            'id' => $this->id,
            'page_name' => $this->page_name,
            'description' => str_limit($this->description, 50, '...'),
            'cover_image' => $this->CoverImagePath,
            'category' => str_replace('_', ' ', ucwords($this->classRoomPageCategory->name)),
            'like_count' => $this->classRoomPageDetail()->where('like', 1)->count(),
            'unlike_count' => $this->classRoomPageDetail()->where('dislike', 1)->count(),
            'follow_count' => $this->classRoomPageDetail()->where('is_following', 1)->count(),
        ];
        $pagedetail = ClassRoomPageDetail::where([['user_id', Auth::id()], ['page_id', $this->id]])->first();
        if ($pagedetail != null) {
            $array['is_following'] = $pagedetail->is_following;
            $array['like'] = $pagedetail->like;
            $array['dislike'] = $pagedetail->dislike;
        }

        return $array;
    }
}
