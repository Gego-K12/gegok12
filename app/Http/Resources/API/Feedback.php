<?php

namespace App\Http\Resources\API;

use App\Http\Resources\API\FeedbackMessage as FeedbackMessageResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Feedback extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $show = FeedbackMessageResource::collection($this->feedbackMessage);

        /*if($this->latestMessage->is_seen == 0)
        {
            $status = 'Not Seen';
        }
        else
        {
            $status = ucwords(str_replace('_', ' ', $this->latestMessage->is_seen));
        }*/

        /*
                if($this->latestMessage->is_seen == 0)
                {
                    $status = 'Not Seen';
                }
                elseif($this->latestMessage->is_seen == 'has_seen')
                {
                    $status = 'Seen';
                }
                else
                {
                     $status = 'Action Taken';
                }*/

        return
        [
            'feedback_id' => $this->id,
            // 'messages'      => $show,
            'messages' => $this->latestMessage->message,
            'category' => ucwords(str_replace('_', ' ', (str_replace('/', ' / ', $this->latestMessage->category)))),
            'status' => $this->latestMessage->is_seen != '0' ? ucwords(str_replace('_', ' ', $this->latestMessage->is_seen)) : 'Not Seen',
            'created_on' => date('d-m-Y H:i:s', strtotime($this->created_at)),
            // 'last_reply_by' => $this->feedbackMessage->last()->user->FullName,
            // 'last_reply_on' => date('d-m-Y H:i:s',strtotime($this->feedbackMessage->last()->created_at)),
        ];
    }
}
