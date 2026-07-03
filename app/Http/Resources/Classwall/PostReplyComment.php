<?php

namespace App\Http\Resources\Classwall;

use App\Models\PostCommentDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostReplyComment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array =
        [
            //
            'id' => $this->id,
            'user_id' => $this->user_id,
            'comment_id' => $this->post->id,
            'post_id' => $this->entity_id,
            'reply_comments' => $this->comments,
            'reply_attachment' => $this->attachment_file == '' ? null : $this->AttachmentPath,
            'user_name' => $this->user->name,
            'user_fullname' => $this->user->FullName,
            'user_avatar' => $this->user->userprofile->AvatarPath,
            'replied_at' => $this->updated_at->diffForHumans(),
            'like_count' => $this->CommentLikeCount,
            'unlike_count' => $this->CommentUnlikeCount,
        ];
        $postCommentDetail = PostCommentDetail::where('post_comment_id', $this->id)->first();
        if ($postCommentDetail != null) {
            $array['like'] = $postCommentDetail->like;
            $array['unlike'] = $postCommentDetail->unlike;
        }

        return $array;
    }
}
