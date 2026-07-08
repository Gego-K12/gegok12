<?php

namespace App\Http\Resources\Classwall;

use App\Models\PostDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ClassPost extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->visibility == 'all_class') {
            $visibility = str_replace('_', ' ', ucwords($this->visibility));
        } elseif ($this->visibility == 'select_class') {
            $visibility = $this->StandardLink->StandardSection;
        }

        $array =
        [
            'id' => $this->id,
            'description' => $this->description,
            'post_created_at' => Carbon::parse($this->post_created_at)->diffForHumans(),
            'attachments' => $this->AttachmentPath,
            'visibility' => $visibility,
            'created_by' => $this->created_by,
        ];

        $array['is_posted'] = $this->is_posted;
        $post_detail = PostDetail::where([['user_id', Auth::id()], ['post_id', $this->id]])->first();
        $array['like'] = $post_detail->like;
        $array['unlike'] = $post_detail->unlike;
        $array['save'] = $post_detail->save;
        $array['unsave'] = $post_detail->unsave;
        $array['auth_id'] = Auth::id();
        $array['like_count'] = $this->postDetail == null ? null : $this->postDetail->ByLikeCount($this->id);
        $array['unlike_count'] = $this->postDetail == null ? null : $this->postDetail->ByUnlikeCount($this->id);
        // $array['comment_list']['comments_count']    = count($this->PostComments);
        // $array['comment_list']['comments']          = $this->PostComments;

        return $array;
    }
}
