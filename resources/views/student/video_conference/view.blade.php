@extends('layouts.video')
@section('content')
<div class="">
     <div class="video-wrapper" id="media-div">
     </div>
 </div>

 <div style="display: none;" id="fileshare_container" class="">
    <p>File share</p>
  </div>

<div style="display: none;" id="whiteboard_container" class="">
<div>
 <canvas id="can" width="400" height="400" style="position:absolute;top:10%;left:10%;border:2px solid;background-color: white"></canvas>
</div>
<div>
       <!--  <div style="position:absolute;top:12%;left:43%;">Choose Color</div>
        <div style="position:absolute;top:15%;left:45%;width:10px;height:10px;background:green;" id="green" onclick="color(this)"></div>
        <div style="position:absolute;top:15%;left:46%;width:10px;height:10px;background:blue;" id="blue" onclick="color(this)"></div>
        <div style="position:absolute;top:15%;left:47%;width:10px;height:10px;background:red;" id="red" onclick="color(this)"></div>
        <div style="position:absolute;top:17%;left:45%;width:10px;height:10px;background:yellow;" id="yellow" onclick="color(this)"></div>
        <div style="position:absolute;top:17%;left:46%;width:10px;height:10px;background:orange;" id="orange" onclick="color(this)"></div>
        <div style="position:absolute;top:17%;left:47%;width:10px;height:10px;background:black;" id="black" onclick="color(this)"></div>
        <div style="position:absolute;top:20%;left:43%;">Eraser</div>
        <div style="position:absolute;top:22%;left:45%;width:15px;height:15px;background:white;border:2px solid;" id="white" onclick="color(this)"></div>
        <img id="canvasimg" style="position:absolute;top:10%;left:52%;" style="display:none;">
        <input type="button" value="save" id="btn" size="30" onclick="save()" style="position:absolute;top:55%;left:10%;">
        <input type="button" value="clear" id="clr" size="23" onclick="erase()" style="position:absolute;top:55%;left:15%;"> -->
  </div>
</div>

 <div style="display: none;" id="chat_container" class="container">
    <div class="chat_box">
      <div class="head">
        <div class="user">
          <div class="avatar">
            <img class="w-8 h-8" src="{{Auth::user()->userprofile->AvatarPath}}" />
          </div>
          <div class="name">{{Auth::user()->FullName}}</div>
        </div>
        <ul class="bar_tool">
          <li><span class="alink"><i class="fas fa-phone"></i></span></li>
          <li><span class="alink"><i class="fas fa-video"></i></span></li>
          <li><span class="alink"><i class="fas fa-ellipsis-v"></i></span></li>
        </ul>
      </div>
      <div id="message_body" class="body chat-log">
      <!-- Chat content -->
      </div>
      <div class="foot">
        <input id="txt_message" type="text" class="msg" placeholder="Type a message..." />
        <button id="send_messsage" type="submit">Send</button>
      </div>
    </div>
  </div>
<style type="text/css">
  .video-space video
  {
    width: 100%;
    height: 378px;
  }

  .chat-log {
  overflow: auto;
  height: calc(45vh);
}

  .blog {
  font-size: 14px;
  font-weight: bold;
  text-align: center;
  position: absolute;
  bottom: 15px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 1;
}
.alink {
  display: inline-block;
  text-align: center;
  cursor: pointer;
}
input[type="text"],
button {
  padding: 4px 8px;
  border: 0;
  outline: 0;
}
button {
  background-color: transparent;
  cursor: pointer;
}
button:hover i {
  color: #79c7c5;
  transform: scale(1.2);
}

/* container */
.container {
  width: 450px;
  height: auto;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1;
  border-radius: 10px;
  background-color: #f9fbff;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
  overflow: hidden;
}

/* chat_box */
.chat_box {
  display: flex;
  flex-direction: column;
  height: 100%;
}
.chat_box > * {
  padding: 16px;
}

/* head */
.head {
  display: flex;
  align-items: center;
}
.head .user {
  display: flex;
  align-items: center;
  flex-grow: 1;
}
.head .user .avatar {
  margin-right: 8px;
}
.head .user .avatar img {
  display: block;
  border-radius: 50%;
}
.head .bar_tool {
  display: flex;
}
.head .bar_tool i {
  padding: 5px;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* body */
.body {
  flex-grow: 1;
  background-color: #eee;
}
.body .bubble {
  display: inline-block;
  padding: 10px;
  margin-bottom: 5px;
  border-radius: 15px;
}
.body .bubble p {
  color: #f9fbff;
  font-size: 14px;
  text-align: left;
  line-height: 1.4;
}
.body .incoming {
  text-align: left;
}
.body .incoming .bubble {
  background-color: #b2b2b2;
}
.body .outgoing {
  text-align: right;
}
.body .outgoing .bubble {
  background-color: #79c7c5;
}

/* foot */
.foot {
  display: flex;
}
.foot .msg {
  flex-grow: 1;
}

@keyframes bounce {
  50% {
    transform: translate(0, 5px);
  }
  100% {
    transform: translate(0, 0);
  }
}
.ellipsis {
  display: inline-block;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  background-color: #b7b7b7;
}
.dot_1 {
  /* animation: name duration timing-function delay iteration-count */
  animation: bounce 0.8s linear 0.1s infinite;
}
.dot_2 {
  animation: bounce 0.8s linear 0.2s infinite;
}
.dot_3 {
  animation: bounce 0.8s linear 0.3s infinite;
}

</style>
@endsection

@push('scripts')
<link href="{{url('css/fullcalendar/jquery.toastmessage.css')}}" rel="stylesheet" type="text/css">
<script src="{{url('/js/jquery.toastmessage.js')}}"></script> 
<!-- <script src="//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js"></script> -->
 <script src="//sdk.twilio.com/js/video/releases/2.17.1/twilio-video.min.js"></script>
<script type="text/javascript">

var dataarray = new Array();
const dataTrack = new Twilio.Video.LocalDataTrack();
const dataTrackPublished = {};
dataTrackPublished.promise = new Promise((resolve, reject) => {
  dataTrackPublished.resolve = resolve;
  dataTrackPublished.reject = reject;
});

  document.addEventListener('contextmenu', event => event.preventDefault());
    Twilio.Video.createLocalTracks({
       audio: true,
       video: { height: 380, frameRate: 24, width: 720 }
    }).catch(function(error) {
      window.location.href = '{{url("student/video-conference")}}';
    }).then(function(localTracks) {
       dataarray.push(localTracks[0]);
       dataarray.push(localTracks[1]);
       dataarray.push(dataTrack);
                  
       return Twilio.Video.connect('{{ $accessToken }}', {
            name: '{{ $roomName }}',
            tracks: dataarray,
            audio: true,
            video: { height: 380, frameRate: 24, width: 720 },
        bandwidthProfile: {
        video: {
          mode: 'grid',
          maxTracks: 10,
          renderDimensions: {
            high: {height:1080, width:1920},
            standard: {height:720, width:1280},
            low: {height:176, width:144}
          }
        }
      },
      maxAudioBitrate: 16000,
      networkQuality: {local:1, remote: 1}
      });
    }).then(function(room) {
       console.log('Successfully joined a Room: ', room.name);

       room.participants.forEach(participantConnected);

       var previewContainer = document.getElementById(room.localParticipant.sid);
       if (!previewContainer || !previewContainer.querySelector('video')) {
           participantConnected(room.localParticipant);
       }

       room.on('participantConnected', function(participant) {
           console.log("Joining: '"  +participant.identity   +"'");
           participantConnected(participant);
           $().toastmessage('showSuccessToast',  participant.identity+' Joined');
       });

       room.on('participantDisconnected', function(participant) {
           console.log("Disconnected: '"  + participant.identity  + "'");
           participantDisconnected(participant);
           $().toastmessage('showNoticeToast',  participant.identity+' Left');
       });

       room.localParticipant.on('trackPublished', publication => {
        if (publication.track === dataTrack) {
          dataTrackPublished.resolve();
        }
      });

      room.localParticipant.on('trackPublicationFailed', (error, track) => {
        if (track === dataTrack) {
          dataTrackPublished.reject(error);
        }
      });

      $(document).on('click','#send_messsage',function(){
                var messag_content = $('#txt_message').val();
                var timestamp=new Date().getTime();
                sendMessage(messag_content,room.localParticipant.identity,timestamp);
            });

       $(document).on('click','#diable_message',function(){
                var check  = $(this).data('type');
                if(check=='mute'){
                    $('#enable_message_svg').show();
                    $('#disable_message_svg').hide();
                    $('#chat_container').show();
                    $(this).data('type','unmute');
                }
                else
                {
                    $('#enable_message_svg').hide();
                    $('#disable_message_svg').show();
                    $('#chat_container').hide();
                    $(this).data('type','mute');
                }
       });

       $(document).on('click','#switch_fileshare',function(){
                var check  = $(this).data('type');
                if(check=='mute'){
                    $('#enable_fileshare_svg').show();
                    $('#disable_fileshare_svg').hide();
                    $('#fileshare_container').show();
                    $(this).data('type','unmute');
                }
                else
                {
                    $('#enable_fileshare_svg').hide();
                    $('#disable_fileshare_svg').show();
                    $('#fileshare_container').hide();
                    $(this).data('type','mute');
                }
       });

       $(document).on('click','#diable_audio',function(){
          var check  = $(this).data('type');
          if(check=='mute'){
            $('#enable_audio_svg').show();
            $('#disable_audio_svg').hide();
            $(this).attr('title','Ummute Me');
            $(this).data('type','unmute');
          }else{
            $('#enable_audio_svg').hide();
            $('#disable_audio_svg').show();
            $(this).attr('title','Mute Me');
            $(this).data('type','mute');
          }
          muteOrUnmuteYourMedia(room, 'audio', check);
       });

       $(document).on('click','#mute_all',function(){
              //alert('sddsd');
                var check  = $(this).data('type');
                if(check=='mute'){
                    $('#enable_muteall_svg').show();
                    $('#disable_muteall_svg').hide();
                    $(this).data('type','unmute');
                }
                else
                {
                    $('#enable_muteall_svg').hide();
                    $('#disable_muteall_svg').show();
                    $(this).data('type','mute');
                }
                 muteOrUnmuteAllMedia(room, 'audio', check);
       });

       $(document).on('click','#diable_video',function(){
          var check  = $(this).data('type');
          if(check=='mute'){
            $('#enable_video_svg').hide();
            $('#disable_video_svg').show();
            $(this).attr('title','Enable Video');
            $(this).data('type','unmute');
          }else{
            $('#enable_video_svg').show();
            $('#disable_video_svg').hide();
            $(this).attr('title','Disable Video');
            $(this).data('type','mute');
          }
         muteOrUnmuteYourMedia(room, 'video', check);
       });


       $(document).on('click','#leave_conference',function(){
            
       var r = confirm("Are you sure want to leave the room ?");
                if (r == true) {
                   room.localParticipant.tracks.forEach(publication => {
                       if (publication.track.kind !== 'data') {
                        publication.track.stop();
                        const attachedElements = publication.track.detach();
                        attachedElements.forEach(element => element.remove());
                      }
                    });
                    document.getElementById(room.localParticipant.sid).remove();
                    room.disconnect();
                     window.location.href = '{{url("student/video-conference")}}';
                }
       });

    });

function sendMessage(message,sender,timestamp) {
  var messagetrack=JSON.stringify({"chat":{
        message: message,
        sender:sender,
        timestamp:timestamp,
        type:"SEND"
      }});
  dataTrackPublished.promise.then(() => dataTrack.send(messagetrack));
   $("#message_body").append("<div class='outgoing'><div class='bubble lower'><p>" +sender+ "</p><p c>" +message+ "</p></div></div>");
   $("#txt_message").val('');
  //console.log(message);
}
    // additional functions will be added after this point
    function participantConnected(participant) {

       participant.on('trackSubscribed', track => {
              console.log(`Participant "${participant.identity}" added ${track.kind} Track ${track.sid}`);
              if (track.kind === 'data') {
                track.on('message', data => {
                  console.log(data);
                  console.log(typeof data);
                   if (typeof data==='object' || data.toString() === 'Done!'){
                   arrayBufferToBase64(data,'png','myfile');
                   }
                   if (typeof data!=='object'  && data.toString() !== 'Done!'){
                  console.log(JSON.parse(data));
                  let msg_obj=JSON.parse(data);
                
                  console.log(msg_obj);
                  if(typeof msg_obj.chat!=='undefined'){
                  $("#message_body").append("<div class='incoming'><div class='bubble'><p>" +msg_obj.chat.sender+ "</p><p>" +msg_obj.chat.message+ "</p></div></div>");
                    }

                     if(typeof msg_obj.sharefile!=='undefined')
                    {
                      if(typeof msg_obj.sharefile.name!=='undefined'){
                       console.log(msg_obj.sharefile.name)
                        sharefileName(msg_obj.sharefile.name,msg_obj.sharefile.extension,msg_obj.sharefile.filetype);
                      }
                    }
                  if(typeof msg_obj.whiteboard!=='undefined'){
                    if(typeof msg_obj.whiteboard.status!=='undefined')
                    {
                      console.log(msg_obj.whiteboard.status);
                      if(msg_obj.whiteboard.status==true){
                         $('#enable_whiteboard_svg').show();
                         $('#disable_whiteboard_svg').hide();
                         $('#whiteboard_container').show();
                         $(this).data('type','unmute');
                      }
                      if(msg_obj.whiteboard.status==false){
                         $('#enable_whiteboard_svg').hide();
                         $('#disable_whiteboard_svg').show();
                         $('#whiteboard_container').hide();
                         $(this).data('type','mute');
                      }
                    }
                    if(typeof msg_obj.whiteboard.cordinats!=='undefined' && typeof msg_obj.whiteboard.data!=='undefined'){
                      console.log(msg_obj.whiteboard.cordinats);
                      init(msg_obj.whiteboard.data, msg_obj.whiteboard.cordinats);
                     }
                    if(typeof msg_obj.whiteboard.color!=='undefined')
                    {
                       console.log(msg_obj.whiteboard.color)
                        color(msg_obj.whiteboard.color);
                    }
                    if(typeof msg_obj.whiteboard.clear!=='undefined')
                    {
                      if(msg_obj.whiteboard.clear==true){
                         color(msg_obj.whiteboard.color);
                         erase();
                      }
                    }
                  }
                }
                });
              }
            });

       console.log('Participant "%s" connected', participant.identity);
       // <div style='clear:both'>" +participant.identity+ "</div><div><a href='javascript:void(0);' data-type='mute'id='diable_audio'>Audio</a> | <a href='javascript:void(0);' data-type='mute' id='diable_video'>Video</a> | <a href='javascript:void(0);' id='leave_conference'>Leave</a></div>

       const div = document.createElement('div');
       div.id = participant.sid;
       div.setAttribute("style", "float: left; margin: 10px;");
       div.innerHTML = "<div><div class='bg-gray-200 border border-white relative'><div class='absolute bottom-0'><p class='bg-gray-100 px-2 py-1 text-gray-700 text-xs'>" +participant.identity+ "</p></div><div class='video-space' id='video_"+participant.sid+"'></div></div></div>";

       document.getElementById('media-div').appendChild(div);

            participant.tracks.forEach(publication => {
                  if (publication.track) {
                    document.getElementById('video_'+participant.sid).appendChild(publication.track.attach());
                  }
            });

           participant.on('trackSubscribed', track => {
              document.getElementById('video_'+participant.sid).appendChild(track.attach());
            });

      /* participant.tracks.forEach(function(track) {
          trackAdded(div, track, participant.sid)
       });

       participant.on('trackAdded', function(track) {
           trackAdded(div, track, participant.sid)
       });*/

       participant.on('trackRemoved', trackRemoved);
      
    }

    function participantDisconnected(participant) {
       console.log('Participant "%s" disconnected', participant.identity);

        participant.tracks.forEach(publication => {
            if (publication.track) {
             // document.getElementById('video_'+participant.sid).appendChild(publication.track.attach());
              publication.track.detach().forEach( function(element) { element.remove() });
            }
          });

      // participant.tracks.forEach(trackRemoved);
       document.getElementById(participant.sid).remove();
    }

    function trackAdded(div, track, id) {
      //console.log(track.kind);
             if (track.kind !== 'data') {
            document.getElementById('video_'+id).appendChild(track.attach());
            }
             $('video').attr('controls', 'controls');
       // div.appendChild(track.attach('#video_'+id));
       // $('#video_'+id).append(track.attach());
       // var video = div.getElementsByTagName("video")[0];
       // if (video) {
       //     video.setAttribute("style", "max-width:300px;");
       // }
    }

    function trackRemoved(track) {
        if (track.kind !== 'data') {
            track.detach().forEach( function(element) { element.remove() });
        }
    }

    function muteOrUnmuteYourMedia(room, kind, action) {
    const publications = kind === 'audio'
      ? room.localParticipant.audioTracks
      : room.localParticipant.videoTracks;

    publications.forEach(function(track) {
      if (action === 'mute') {
        track.disable();
      } else {
        track.enable();
      }
    });
  }


  var canvas, ctx, flag = false,
        prevX = 0,
        currX = 0,
        prevY = 0,
        currY = 0,
        dot_flag = false;

    var x = "black",
        y = 2;
    
    function init(datass,ees) {
        canvas = document.getElementById('can');
        ctx = canvas.getContext("2d");
        w = canvas.width;
        h = canvas.height;

        console.log(datass, ees);

        findxy(datass, ees);
    
        /*canvas.addEventListener("mousemove", function (e) {
            findxy('move', e)
        }, false);
        canvas.addEventListener("mousedown", function (e) {
            findxy('down', e)
        }, false);
        canvas.addEventListener("mouseup", function (e) {
            findxy('up', e)
        }, false);
        canvas.addEventListener("mouseout", function (e) {
            findxy('out', e)
        }, false);*/
    }
    
    function color(obj) {
         switch (obj) {
            case "green":
                x = "green";
                break;
            case "blue":
                x = "blue";
                break;
            case "red":
                x = "red";
                break;
            case "yellow":
                x = "yellow";
                break;
            case "orange":
                x = "orange";
                break;
            case "black":
                x = "black";
                break;
            case "white":
                x = "white";
                break;
        }
        if (x == "white") y = 14;
        else y = 2;

    
    }
    
    function draw() {
        ctx.beginPath();
        ctx.moveTo(prevX, prevY);
        ctx.lineTo(currX, currY);
        ctx.strokeStyle = x;
        ctx.lineWidth = y;
        ctx.stroke();
        ctx.closePath();
    }
    
    function erase() {
       // var m = confirm("Want to clear");
       // if (m) {
            ctx.clearRect(0, 0, w, h);
            document.getElementById("canvasimg").style.display = "none";
        //}
    }
    
    function save() {
        document.getElementById("canvasimg").style.border = "2px solid";
        var dataURL = canvas.toDataURL();
        document.getElementById("canvasimg").src = dataURL;
        document.getElementById("canvasimg").style.display = "inline";
    }
    
    function findxy(res, e) {
      //sendwhiteboard(res, e);
      console.log(e.clientX);
        if (res == 'down') {
            prevX = currX;
            prevY = currY;
            currX = e.clientX - canvas.offsetLeft;
            currY = e.clientY - canvas.offsetTop;
    
            flag = true;
            dot_flag = true;
            if (dot_flag) {
                ctx.beginPath();
                ctx.fillStyle = x;
                ctx.fillRect(currX, currY, 2, 2);
                ctx.closePath();
                dot_flag = false;
            }
        }
        if (res == 'up' || res == "out") {
            flag = false;
        }
        if (res == 'move') {
            if (flag) {
                prevX = currX;
                prevY = currY;
                currX = e.clientX - canvas.offsetLeft;
                currY = e.clientY - canvas.offsetTop;
                draw();
            }
        }
       // console.log(prevX);
        //console.log(prevY);
    }
    var FileName;
    var extension;
    var FileType;

    function sharefileName(name,extension,filetype)
    {
      FileName=name;
      extension=extension;
      FileType=filetype;
      console.log('FileName', FileName);
      console.log('extension', extension);
       console.log('FileType', FileType);
    }

   var fileChunks = [];
    function arrayBufferToBase64(data, Filetype, fileName) {
   
      if (data.toString() === 'Done!') {
    // Once, all the chunks are received, combine them to form a Blob
    const file = new Blob(fileChunks,{type:FileType});
  
    console.log('Received', file);
    // Download the received file using downloadjs
    //download(file, 'test.png');

     const url = window.URL.createObjectURL(file);

        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", FileName); //or any other extension
        document.body.appendChild(link);
        link.click();
         console.log('link', link);
        //window.open(url,'Download',"resizable,scrollbars,status");
       window.open(url, '_blank');
        fileChunks=[];
  }
  else {
    // Keep appending various file chunks 
    fileChunks.push(data);
  }


    }


</script>
@endpush