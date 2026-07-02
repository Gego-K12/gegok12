<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Student;

use App\Http\Requests\Classwall\PostRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Traits\Common;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Models\StudentAcademic;
use Exception;

class FeedController extends Controller
{
    //
    use LogActivity;
    use Common;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {       
        $student_id=Auth::id();

        $tags = Tag::join('post_tags', 'tags.id', '=', 'post_tags.tag_id')
        // group by tags.id in order to count number of rows in join and to get each tag only once
        ->groupBy('tags.id')
        // get only columns from tags table along with aggregate COUNT column    
        ->select(['tags.*', \DB::raw('COUNT(*) as cnt')])
        // order by count in descending order
        ->orderBy('cnt', 'desc')
        ->take(20)
        ->get();

        $class=StudentAcademic::where('user_id',$student_id)->first();

     $birthday = $this->getFilePath('uploads/images/birthday.jpg');
     $anniversary = $this->getFilePath('uploads/images/work_anniversary.jpg');
     $exam = $this->getFilePath('uploads/images/exam-banner.jpg');

      
        
        $feeds=Post::where('visible_for',$class->standardLink_id)->orWhere('visibility','all_class')->orderBy('posted_at','DESC')->paginate(10);

         

        
        return view('/student/feed/feed',['feeds'=>$feeds,'tags'=>$tags,'birthday'=>$birthday, 'anniversary'=>$anniversary,'exam'=>$exam]);
    }

     public function filter(Request $request)
    {

         $tags = Tag::join('post_tag', 'tags.id', '=', 'post_tag.tag_id')
        // group by tags.id in order to count number of rows in join and to get each tag only once
        ->groupBy('tags.id')
        // get only columns from tags table along with aggregate COUNT column    
        ->select(['tags.*', \DB::raw('COUNT(*) as cnt')])
        // order by count in descending order
        ->orderBy('cnt', 'desc')
        ->take(20)
        ->get();


     $birthday = $this->getFilePath('uploads/images/birthday.jpg');
     $anniversary = $this->getFilePath('uploads/images/work_anniversary.jpg');
     $exam = $this->getFilePath('uploads/images/exam-banner.jpg');
        if($request->list!='')
        {
            $category=$request->list;

            $feeds=Post::where('visibility',$category)->get();
        }

        elseif($request->search!='')
        {
            $category=$request->search;

            $tags=Tag::where('tag_name',$category)->first();
            
            $post_tag=PostTag::where('tag_id',$tags->id)->pluck('post_id')->toArray();
            $feeds=Post::whereIn('id',$post_tag)->get();            

        }

          return view('/student/feed/filter',['feeds'=>$feeds,'tags'=>$tags,'birthday'=>$birthday, 'anniversary'=>$anniversary,'exam'=>$exam]);
    }
}