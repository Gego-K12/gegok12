<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Admin;

use App\Http\Resources\ShowVideo as ShowVideoResource;
use App\Events\Notification\SchoolNotificationEvent;
//use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Http\Requests\MediafileUpdateRequest;
use App\Http\Requests\MediafileRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\VideoRequest;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Events\PushEvent;
use App\Traits\Common;
use App\Models\Video;
use App\Models\Audio;
use Exception;
use Log;

/**
 * Controller for managing media files (videos, audio, images) in the admin panel.
 */
class VideosController extends Controller
{
    //use HasMediaTrait;
    use InteractsWithMedia;
    use LogActivity;
    use Common;

    /**
     * Show media file dashboard counts.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $count = Video::where('school_id',Auth::user()->school_id)->count();
        
        return view('/admin/mediafiles/index',[ 'count' => $count  ]);
    }

    /**
     * List files of a specific type for the authenticated school.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $type
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(Request $request, $type)
    {
        //
        $files = Video::where([['school_id',Auth::user()->school_id],['type',$type]]);
        if(count((array)\Request::getQueryString())>0)
        {
            if($request->standardLink_id != '')
            { 
                $files = $files->where('standardLink_id',$request->standardLink_id);
            }

            if($request->search != '')
            { 
                $files = $files->where('name','LIKE','%'.$request->search.'%')->orWhere('description','LIKE','%'.$request->search.'%');
            }
        }

        $files = $files->get();

        $files = ShowVideoResource::collection($files);

        return $files;    
    }

    /**
     * Fetch available standard links for the authenticated school.
     *
     * @return mixed
     */
    public function standardlist()
    {
      $standardLinks = SiteHelper::getStandardLinkList(Auth::user()->school_id);
      return $standardLinks;  
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         \Session::put('videopath_urls',[]);
         \Session::put('audiopath_urls',[]);
         \Session::put('recording_urls',[]);
        $count          = Video::where('school_id',Auth::user()->school_id)->count();
        $subscription   = Subscription::where('school_id',Auth::user()->school_id)->first();
        $standardLinks  = SiteHelper::getStandardLinkList(Auth::user()->school_id);

        return view('/admin/mediafiles/create1',[ 'count' => $count , 'subscription' => $subscription,'standardLinks' => $standardLinks ]);
    }

    /**
     * Persist raw audio recording data for later association.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function save(Request $request)
    {
        try
        {
            $folder     = Auth::user()->school->slug.'/uploads/files';
            $filename   = 'audio_'.date('d_m_Y_H_i_s').'.'.$request->encoding;
            $path       = $this->putContentsByFilename($folder,$request->file,$filename);
            \Session::put('path',$path);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Persist raw video recording data for later association.
     *
     * @param \App\Http\Requests\VideoRequest $request
     * @return void
     */
    public function videostore(VideoRequest $request)
    {
        try
        {
            $folder     = Auth::user()->school->slug.'/uploads/files';
            $filename   = 'video_'.date('d_m_Y_H_i_s').'_video.mp4';
            $videopath  = $this->putContentsByFilename($folder,$request->file,$filename);

            \Session::put('videopath',$videopath);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Store an uploaded image as a media record.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function storeimage(Request $request)
    {
         $bigfolder = Auth::user()->school->slug.'/files/large';
         $path   = $this->uploadFile($bigfolder,$request->file);
           $video = new Video;

            $video->school_id       = Auth::user()->school_id;
            $video->media           = 'media_upload';
            //$video->standardLink_id = $request->standardLink_id;
            $video->name            = 'image';
            $video->description     = 'image';
            $video->type            = 'image';
            $video->url             = $path;
                     
            $video->save(); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MediafileRequest $request)
    {
        try
        {
            $school_id = Auth::user()->school_id;
            $path='';
            if($request->type=='image')
            {
                $files = $request->file('images');
                if($request->hasFile('images'))
                {
                    foreach($files as $file)
                    {
                        $bigfolder = Auth::user()->school->slug.'/files/large';
                        $thumbfolder=Auth::user()->school->slug.'/files/small';

                        $pathToImage = $this->uploadFile($bigfolder,$file);

                        $video = new Video;

                        $video->school_id       = $school_id;
                        $video->media           = $request->media;
                        $video->standardLink_id = $request->standardLink_id;
                        $video->name            = $request->name;
                        $video->description     = $request->description;
                        $video->type            = $request->type;
                        $video->url             = $pathToImage;
                     
                        $video->save(); 

                        $media=$video->addMedia($file)->toMediaCollection('images', env('FILESYSTEM_DISK'));
                       
                        $thumb=$media->getPath('thumb');
                        $path = $this->uploadFile($thumb,$file);
                        $video->thumb_file=$thumb;
                        $video->update();                          
                    }                    
                }
            }
            elseif($request->type=='video')
            {
                $video = new Video;

                $video->school_id       = $school_id;
                $video->media           = $request->media;
                $video->standardLink_id = $request->standardLink_id;
                $video->name            = $request->name;
                $video->description     = $request->description;
                $video->type            = $request->type;
                $video->media_type      = $request->video_type;
  
                if($request->url==null)
                { 
                    $video->url = \Session::get('videopath');  
                }
                else
                {
                    $video->url = $request->url;
                }
                $video->save();
                \Session::save();
            }
            else
            {
                $video = new Video;

                $video->school_id       = $school_id;
                $video->media           = $request->media;
                $video->standardLink_id = $request->standardLink_id;
                $video->name            = $request->name;
                $video->description     = $request->description;
                $video->type            = $request->type;
                $video->media_type      = $request->audio_type;

                if($request->audiofile != '')
                {
                    $folder = Auth::user()->school->slug.'/uploads/files';
                    $path = $this->uploadFile($folder,$request->audiofile);
                    $video->url = $path;
                }
                else
                {
                    $video->url = \Session::get('path');
                }
                $video->save();
            }
              
            \Session::forget('path');
            \Session::forget('videopath');

            $data=[];
            $data['school_id']  = Auth::user()->school_id;
            $data['message']    = 'New File Added';
            $data['type']       = 'video';

            event(new PushEvent($data));

            $array = [];

            $array['school_id'] = Auth::user()->school_id;
            $array['details'] = trans('notification.file_add_success_msg');
            
            event(new SchoolNotificationEvent($array));

            $message=trans('messages.add_success_msg',['module' => 'Files']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $video,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_FILE,
                $message
            ); 

            \Session::put('successmessage',$message);
            return redirect('/admin/files');
        }
        catch (Exception $e) 
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Return all media files across schools (likely for API usage).
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function view()
    {
        //
        $videos = Video::get();
        $videos = ShowVideoResource::collection($videos);

        return $videos;
    }

    /**
     * Show a media file's viewers list.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function viewers($id)
    {
        $video = Video::where('id',$id)->first();
        $viewers=$video->viewers()->latest()->paginate(10);

        return view('/admin/mediafiles/view',['video' => $video,'viewers' => $viewers]); 
    }

    /**
     * Display edit form for a media file belonging to the authenticated school.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $videos = Video::where('id',$id)->where('school_id',Auth::user()->school_id)->first();

        $standardLinks = SiteHelper::getStandardLinkList(Auth::user()->school_id);

        return view('/admin/mediafiles/edit',['videos'=>$videos,'standardLinks'=>$standardLinks]);  
    }

    /**
     * Update an existing media file.
     *
     * @param \App\Http\Requests\MediafileUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function update(MediafileUpdateRequest $request,$id)
    {
        $video = Video::where('id',$id)->first();
        try
        {
            $path='';
            if($request->type=='image')
            {
                $files = $request->file('images');
                if($request->hasFile('images'))
                {
                    if(count($files) > 0)
                    {
                        foreach($files as $file)
                        {
                            $bigfolder = Auth::user()->school->slug.'/files/large';
                            $thumbfolder=Auth::user()->school->slug.'/files/small';

                            $pathToImage = $this->uploadFile($bigfolder,$file);
                            
                            $video->media           = $request->media;
                            $video->standardLink_id = $request->standardLink_id;
                            $video->name            = $request->name;
                            $video->description     = $request->description;
                            $video->type            = $request->type;
                            $video->url             = $pathToImage;
                         
                            $video->save(); 
                            $media=$video->addMedia($file)->toMediaCollection('images', env('FILESYSTEM_DISK'));
                           
                            $thumb=$media->getPath('thumb');
                            $video->thumb_file=$thumb;
                            $video->update();                          
                        }  
                    }                  
                }
            }
            elseif($request->type=='video')
            {
                $video->media           = $request->media;
                $video->standardLink_id = $request->standardLink_id;
                $video->name            = $request->name;
                $video->description     = $request->description;
                $video->type            = $request->type;
                $video->media_type      = $request->video_type;
  
                if($request->url==null)
                {
                    if(\Session::get('videopath') == null)
                    {
                        $video->url = \Session::get('videopath');  
                    }
                    else
                    {
                        $video->url = $video->url;
                    }
                }
                else
                {
                    $video->url = $request->url;
                }
                $video->save();
                \Session::save();
            }
            else
            {
                $video->media           = $request->media;
                $video->standardLink_id = $request->standardLink_id;
                $video->name            = $request->name;
                $video->description     = $request->description;
                $video->type            = $request->type;
                $video->media_type      = $request->audio_type;

                if($request->audiofile != '')
                {
                    $folder = Auth::user()->school->slug.'/uploads/files';
                    $path = $this->uploadFile($folder,$request->audiofile);
                    $video->url = $path;
                }
                else
                {
                    if(\Session::get('path') == null)
                    {
                        $video->url = $video->url;
                    }
                    else
                    {
                        $video->url = \Session::get('path');
                    }
                }
                $video->save();
            }
            
            \Session::forget('path');
            \Session::forget('videopath');

            $data=[];
            $data['school_id']=Auth::user()->school_id;
            $data['message']='New File Added';
            $data['type']='video';

            event(new PushEvent($data));

            $array = [];

            $array['school_id'] = Auth::user()->school_id;
            $array['details'] = trans('notification.file_add_success_msg');
            
            event(new SchoolNotificationEvent($array));

            $message=trans('messages.update_success_msg',['module' => 'Files']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $video,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_FILE,
                $message
            ); 

            \Session::put('successmessage',$message);
            return redirect('/admin/files');
        }
        catch (Exception $e) 
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Show a single media file along with derived URLs and thumbnail.
     *
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        //
        $video = Video::where('id', $id)->where('school_id',Auth::user()->school_id)->first();

        if($video->media_type == 'url')
        {
            $url = $video->url;   
        }
        else
        {
            $url = $video->AttachmentPath;       
        }

        if($video->type=='image')
        {
            $thumb=$video->ThumbPath;
        }
        elseif($video->type=='audio')
        {
            $thumb = $this->getFilePath('uploads/audio.png');
        }
        else
        {
            $thumb = $this->getFilePath('uploads/video.png');
        }
        $videos = [
            'id'            => $video->id,
            'name'          => $video->name,        
            'media'         => ucwords(str_replace('_', ' ', $video->media)), 
            'standard'      => $video->standardLink->StandardSection,     
            'description'   => $video->description,
            'type'          => $video->type,
            'url'           => $url,
            'thumb_file'    => $thumb,
            'downloadurl'   =>"videos/download/".$video->id,
        ];

        return $videos;
    }

    /**
     * Download a media file attachment.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function downloadattachments($id)
    {
        $video = Video::where('id', $id)->where('school_id',Auth::user()->school_id)->first();
        $file=$video->url;
        $path=$this->getFilePathforDownload(env('FILESYSTEM_DISK'),$file);
        $name='Media'.'_'.$video->name.'.jpg';
        $headers = [
            'Content-Disposition' => 'attachment; filename="'. $name.'"',
        ];

        $message= trans('messages.download_success_msg',['module' => 'Files']);

        $ip= $this->getRequestIP();
        $this->doActivityLog(
            $video,
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
            LOGNAME_DOWNLOAD_FILE,
            $message
        );  
            
        //return response()->download($path,$file,$headers); 
        return \Response::make($path, 200, $headers);
    }

    /**
     * Delete a media file if the user is authorized.
     *
     * @param int $id
     * @return array|\Symfony\Component\HttpFoundation\Response|void
     */
    public function destroy($id)
    {
        try
        {
            $video = Video::where('id',$id)->first();
            if(Gate::allows('video',$video))
            {
                $video->delete();

                $message=trans('messages.delete_success_msg',['module' => 'Files']);

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $video,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_FILE,
                    $message
                );
                $res['success'] = $message;
                return $res;
            }
            else
            {
                abort(403);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
