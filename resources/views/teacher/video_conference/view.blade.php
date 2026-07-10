@extends('layouts.video')
@section('content')

    <div class="">
        <div class="video-wrapper" id="media-div">
        </div>
    </div>
<div style="display: none;" id="fileshare_container" class="container p-4">
    <p>File share</p><input type="file" name="" id="file_share">
  </div>

  <div  style="display: none;" id="memberslist_container" class="container  mx-0 float-left p-4">
    <div id="memberslist_section" class="chat-log">
    @foreach($conference->videoConferenceUser as $member)
    <div id="{{'partmember_'.$member->user->FullName}}"  style="display: block;" class="head">
        <div  class="user">
          <!-- <div class="avatar">
            <img class="w-8 h-8" src="{{$member->user->userprofile->AvatarPath}}" />
          </div> -->
          <div id="{{$member->user->FullName}}" class="name">{{$member->user->FullName}}</div>
          <span class="w-3 h-3 rounded-full inline-block bg-red-600 ml-1"></span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
    
<!-- <div class="w-1/4">
 <div class="menu">
       <div class="items">
        <span>
         <a href="#" title="Minimize">&mdash;</a><br>
         <a href="#" title="End Chat">&#10005;</a>
         </span>
     </div>
  </div>
  <div class="agent-face w-1/4">
<div  class="chat message-box">
  <div id="chat_title_chat" class="chat-title">
    
  </div>
  <div class="message-box">
    <textarea id="txt_message" type="text" class="message-input" placeholder="Type message..."></textarea>
    <button id="send_messsage" type="submit" class="message-submit">Send</button>
  </div>
</div>
  </div>
</div> -->
<div style="display: none;" id="whiteboard_container" class="">
<div>
 <canvas id="can" width="400" height="400" style="position:absolute;top:10%;left:10%;border:2px solid;background-color: white;"></canvas>
 </div>
 <div>
        <div style="position:absolute;top:12%;left:43%;">Choose Color</div>
        <div style="position:absolute;top:15%;left:45%;width:10px;height:10px;background:green;" id="green" onclick="color(this)"></div>
        <div style="position:absolute;top:15%;left:46%;width:10px;height:10px;background:blue;" id="blue" onclick="color(this)"></div>
        <div style="position:absolute;top:15%;left:47%;width:10px;height:10px;background:red;" id="red" onclick="color(this)"></div>
        <div style="position:absolute;top:17%;left:45%;width:10px;height:10px;background:yellow;" id="yellow" onclick="color(this)"></div>
        <div style="position:absolute;top:17%;left:46%;width:10px;height:10px;background:orange;" id="orange" onclick="color(this)"></div>
        <div style="position:absolute;top:17%;left:47%;width:10px;height:10px;background:black;" id="black" onclick="color(this)"></div>
        <div style="position:absolute;top:20%;left:43%;">Eraser</div>
        <div style="position:absolute;top:22%;left:45%;width:15px;height:15px;background:white;border:2px solid;" id="white" onclick="color(this)"></div>
        <img id="canvasimg" style="position:absolute;top:10%;left:52%;" style="display:none;">
       <!--  <input type="button" value="save" id="btn" size="30" onclick="save()" style="position:absolute;top:55%;left:10%;"> -->
        <input type="button" value="clear" id="clr" size="23" onclick="erase()" style="position:absolute;top:55%;left:15%;">
    </div>
</div>

<div style="display: none;" id="chat_container" class="container">
    <div class="chat_box">
      <div class="head">
        <div class="user">
          <div class="avatar">
            <img class="w-8 h-8" src="{{$conference->userInfo->userprofile->AvatarPath}}" />
          </div>
          <div class="name">{{$conference->userInfo->FullName}}</div>
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

video::-webkit-media-controls-overlay-play-button {
  display: none;
}

</style>
@endsection

@push('scripts')
    <link href="{{url('css/fullcalendar/jquery.toastmessage.css')}}" rel="stylesheet" type="text/css">
    <script src="{{url('/js/jquery.toastmessage.js')}}"></script> 
   <!--  <script src="//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js"></script> -->
   <script src="//sdk.twilio.com/js/video/releases/2.17.1/twilio-video.min.js"></script>
    <script type="text/javascript">

var dataarray = new Array();
const dataTrack = new Twilio.Video.LocalDataTrack();
const dataTrackPublished = {};
dataTrackPublished.promise = new Promise((resolve, reject) => {
  dataTrackPublished.resolve = resolve;
  dataTrackPublished.reject = reject;
});
var identity = '{{$identity}}';

var membersListArray = [];

        document.addEventListener('contextmenu', event => event.preventDefault());
        Twilio.Video.createLocalTracks({
            audio: true,
            video: true,
            data : true
        }).catch(function(error) {
           alert("Please check camera and microphone connectivity");
            window.location.href = '{{url("teacher/video-conference")}}';
        }).then(function(localTracks) {


            navigator.mediaDevices.getDisplayMedia({
            audio: true,
            video: true,
        }).then(stream => {

                   screenTrack = stream.getTracks()[0];
                   if(stream.getTracks().length>1){
                   dataarray.push(stream.getTracks()[0]);
                   dataarray.push(stream.getTracks()[1]);
                   dataarray.push(localTracks[0]);
                   dataarray.push(dataTrack);
                 }
                 else{
                     dataarray.push(localTracks[0]);
                     dataarray.push(screenTrack);
                     dataarray.push(dataTrack);
                 }
                   
                  
                   //console.log(screenTrack);
                   stream.getVideoTracks()[0].onended = function () {
                      location.reload();
                    };
                   //localTracks.push(screenTrack);
              }).catch(function(){

                dataarray.push(localTracks[0]);
                dataarray.push(localTracks[1]);
                dataarray.push(dataTrack);
                console.log(dataarray);
                

              }).then(function(){

              

                 return Twilio.Video.connect('{{ $accessToken }}', {
                name: '{{ $roomName }}',
                tracks: dataarray,
                audio: true,
                video: { height: 380, frameRate: 24, width: 720 },
                bandwidthProfile: {
                    video: {
                        mode: 'grid',
                        maxTracks: 50,
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

           
            $(document).on('click','#diable_audio',function(){



                var check  = $(this).data('type');
                if(check=='mute'){
                    $('#enable_audio_svg').show();
                    $('#disable_audio_svg').hide();
                    $(this).attr('title','Ummute Me');
                    $(this).data('type','unmute');
                }
                else
                {
                    $('#enable_audio_svg').hide();
                    $('#disable_audio_svg').show();
                    $(this).attr('title','Mute Me');
                    $(this).data('type','mute');
                }
                muteOrUnmuteYourMedia(room, 'audio', check);
                //muteOrUnmuteAllMedia(room, 'audio', check);
            });

             $(document).on('click','.participant_mute',function(){
            console.log('123');
            var check  = $(this).data('settype');
             var participant_sid  = $(this).data('sid');
             console.log(participant_sid);
            if(check=='mute'){
              $(this).text('Ummute');
              $(this).data('settype','unmute');
            }else{
              $(this).text('Mute');
              $(this).data('settype','mute');
            }
            muteOrUnmuteIndiviualMedia(room, 'audio', check,participant_sid);
           //socket.emit('school by user', {type:check});
       });

             $(document).on('click','.participant_admit',function(){
            console.log('123');
            var check  = $(this).data('settype');
            // var participant_sid  = $(this).data('sid');
             var participant_identity  = $(this).data('identity');
             console.log(participant_identity);
            if(check=='admit'){
              //$(this).text('Admitted');  
              $(this).hide();
              AllowParticipant(room, participant_identity);   
            }
            
           //socket.emit('school by user', {type:check});
       });

             $(document).on('click','#mute_all',function(){
             // alert('sddsd')
                var check  = $(this).data('type');
                //alert(check);
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

             $(document).on('click','#switch_whiteboard',function(){
                var check  = $(this).data('type');
                if(check=='mute'){
                    $('#enable_whiteboard_svg').show();
                    $('#disable_whiteboard_svg').hide();
                    $('#whiteboard_container').show();
                    $(this).data('type','unmute');
                    init();
                    sendWhiteboardStatus(true);
                }
                else
                {
                    $('#enable_whiteboard_svg').hide();
                    $('#disable_whiteboard_svg').show();
                    $('#whiteboard_container').hide();
                    $(this).data('type','mute');
                    sendWhiteboardStatus(false);
                }
            });

             $(document).on('click','#switch_memberlist',function(){
                var check  = $(this).data('type');
                if(check=='mute'){
                    $('#enable_memberlist_svg').show();
                    $('#disable_memberlist_svg').hide();
                    $('#memberslist_container').show();
                    $(this).data('type','unmute');
                }
                else
                {
                    $('#enable_memberlist_svg').hide();
                    $('#disable_memberlist_svg').show();
                    $('#memberslist_container').hide();
                    $(this).data('type','mute');
                }
            });

             $(document).on('click','#send_messsage',function(){
                var messag_content = $('#txt_message').val();
                var timestamp=new Date().getTime();
                sendMessage(messag_content,room.localParticipant.identity,timestamp);
            });

             $('#txt_message').keypress(function(event){
                  var keycode = (event.keyCode ? event.keyCode : event.which);
                  if(keycode == '13'){
                      var messag_content = $('#txt_message').val();
                      var timestamp=new Date().getTime();
                      sendMessage(messag_content,room.localParticipant.identity,timestamp);
                  }
              });

             $(document).on('click','#switch_video',function(){
               location.reload();
            });

            $(document).on('click','#diable_video',function(){

                var check  = $(this).data('type');
                //sendMessage(room,'dfsdf');
                if(check=='mute'){
                    $('#enable_video_svg').hide();
                    $('#disable_video_svg').show();
                    $(this).attr('title','Enable Video');
                    $(this).data('type','unmute');
                }
                else
                {
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
                    window.location.href = '{{url("admin/video-conference/status/".$conference->id)}}';
                }
            });



            /*$(document).on('click','#leave_conference',function(){
                var r = confirm("Are you sure want to leave the room ?");
                if (r == true) {
                    room.localParticipant.tracks.forEach(track => {
                       if (track.kind !== 'data') {
                        track.stop();
                        const attachedElements = track.detach();
                        attachedElements.forEach(element => element.remove());
                      }
                    });
                    document.getElementById(room.localParticipant.sid).remove();
                    room.disconnect();
                    window.location.href = '{{url("teacher/video-conference/status/".$conference->id)}}';
                }
            });
*/
           
              });
          
        });

function sendMessage(message,sender,timestamp) {
  var messagetrack=JSON.stringify({"chat":{
        message: message,
        sender:sender,
        timestamp:timestamp,
        type:"SEND"
      }});
var date = new Date(timestamp).toLocaleTimeString("en-US");
console.log(date)
  //console.log(timestamp);
  dataTrackPublished.promise.then(() => dataTrack.send(messagetrack));
  $("#message_body").append("<div class='outgoing'><div class='bubble lower'><p>" +sender+ "</p><p>" +message+ "</p><p>" +date+ "</p></div></div>");
  $("#txt_message").val('');
  //console.log(message);
}

function participantlist(participant)
{
  //<button href='javascript:void(0);' class='participant_mute' data-settype='mute' id='admit_"+participant.sid+"' >Admit</button>
 // alert(participant.identity);
  //document.getElementById('partmember_'+participant.identity).style.visibility = 'hidden';
  $('#partmember_'+participant.identity).hide();

 $("#memberslist_section").prepend("<div id='member_"+participant.identity+"' class='head'><div  class='user'><div class='name'>" +participant.identity+ "</div><button href='javascript:void(0);' class='participant_mute' data-settype='mute' data-sid='"+participant.sid+"' id='muteBypr_'"+participant.sid+">Mute</button><span class='w-3 h-3 rounded-full inline-block bg-green-600 ml-1'></span></div></div>");
}

function AllowParticipant(room,name)
{
  //alert(name);
  membersListArray.push(name);
  room.participants.forEach(function(participant) {
        if(participant.identity.toString()==name.toString()){
        participantConnected(participant);
        sendpermitmessage(membersListArray);
      }
      });
  //membersListArray.push(sid);
  console.log(membersListArray);
}

function sendpermitmessage(identity) {
  console.log('identity',identity);
   var messagetrack=JSON.stringify({"Admitted":true,"allowstatus":identity});
   dataTrackPublished.promise.then(() => dataTrack.send(messagetrack));
  console.log(messagetrack);
}


function sendWhiteboardStatus(status) {
 var messagetrack=JSON.stringify({"whiteboard":{
        status:status,
      }});
  console.log(messagetrack);
  console.log(JSON.parse(messagetrack));
  var sdsd=JSON.parse(messagetrack);
  console.log(sdsd.whiteboard.status);
  dataTrackPublished.promise.then(() => dataTrack.send(messagetrack));
  //console.log(message);
}
 
        // additional functions will be added after this point
        function participantConnected(participant) {
//alert(participant.identity);
if(participant.identity==identity){
 $("#memberslist_section").prepend("<div id='member_"+participant.identity+"' class='head'><div  class='user'><div class='name'>" +participant.identity+"(Host)"+ "</div><span class='w-3 h-3 rounded-full inline-block bg-green-600 ml-1'></span></div></div>");
}
else
{
  //$('#partmember_'+participant.identity).hide();
  document.getElementById('partmember_'+participant.identity).style.display = 'none';
  $("#memberslist_section").prepend("<div id='member_"+participant.identity+"' class='head'><div  class='user'><div class='name'>" +participant.identity+ "</div><button href='javascript:void(0);' class='participant_mute' data-settype='mute' data-sid='"+participant.sid+"' id='muteBypr_'"+participant.sid+">Mute</button><span class='w-3 h-3 rounded-full inline-block bg-green-600 ml-1'></span></div></div>");
}


            participant.on('trackSubscribed', track => {
              console.log(`Participant "${participant.identity}" added ${track.kind} Track ${track.sid}`);
              if (track.kind === 'data') {
                track.on('message', data => {
                  console.log(JSON.parse(data));
                  let msg_obj=JSON.parse(data);
                   let partdate = new Date(msg_obj.chat.timestamp).toLocaleTimeString("en-US");
                 $("#message_body").append("<div class='incoming'><div class='bubble'><p>" +msg_obj.chat.sender+ "</p><p>" +msg_obj.chat.message+ "</p><p>" +partdate+ "</p></div></div>");
                   $().toastmessage('showSuccessToast',  msg_obj.chat.sender+' Send New message');
                });
              }
            });
           
            console.log('Participant "%s" connected', participant.identity);
            // <div style='clear:both'>" +participant.identity+ "</div><div><a href='javascript:void(0);' data-type='mute'id='diable_audio'>Audio</a> | <a href='javascript:void(0);' data-type='mute' id='diable_video'>Video</a> | <a href='javascript:void(0);' id='leave_conference'>Leave</a></div>
            const div = document.createElement('div');
            div.id = participant.sid;
            var mutes = ''; 
             if(participant.identity!=identity){
               var mutes = '<div class="flex"><button   class="participant_mutes bg-gray-100 px-2 py-1 text-gray-700 text-xs" data-settype="mute" data-sid="'+participant.identity+'" id="muteBypr_'+participant.sid+'">Mic</button></br><button href="" class="participant_mutes bg-gray-100 px-2 py-1 text-gray-700 text-xs"  data-settype="mute" data-sid="'+participant.identity+'" id="videoBypr_'+participant.sid+'">video</button><button href="javascript:void(0);" class="participant_mute" data-settype="mute" data-sid="'+participant.sid+'" id="muteBypr_"'+participant.sid+'>Mute</button><div>';
             }
             else
             {
              var mutes = '<button   class="participant_mutes hidden bg-gray-100 px-2 py-1 text-gray-700 text-xs" data-settype="mute" data-sid="'+participant.identity+'" id="muteBypr_'+participant.sid+'">Mic</button></br><button href="" class="participant_mutes hidden bg-gray-100 px-2 py-1 text-gray-700 text-xs"  data-settype="mute" data-sid="'+participant.identity+'" id="videoBypr_'+participant.sid+'">video</button><button href="javascript:void(0);" class="participant_mute hidden" data-settype="mute" data-sid="'+participant.sid+'" id="muteBypr_"'+participant.sid+'>Mute</button>';
             }

            div.setAttribute("style", "float: left; margin: 10px;");
            div.innerHTML = "<div><div class='bg-gray-200 border border-white relative'><div class='absolute bottom-0'><p  class='bg-gray-100 px-2 py-1 text-gray-700 text-xs'>" +participant.identity+ "</p></div><div class='video-space' id='video_"+participant.sid+"'></div></div>"+mutes+"</div>";
            
         if(participant.identity!=identity){
            document.getElementById('media-div').appendChild(div);
          }
          else
          {
            document.getElementById('media-div').prepend(div);
          }

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

            /*participant.tracks.forEach(publication => {
              if (publication.isSubscribed) {
                handleTrackDisabled(publication.track,participant.sid);
              }
              publication.on('subscribed', handleTrackDisabled);
            });*/

           

            participant.on('trackAdded', function(track) {
          
                  console.log(track);
                 track.on('disabled', () => {
                  console.log(participant.sid);
                 // var vid = document.getElementById('video_'+id);
                  // vid.muted = true;
                  if (track.kind === 'audio'){
                     //console.log(track.mediaStreamTrack.enabled);
                     console.log(track);
                     console.log(track.isEnabled);
                  document.getElementById('muteBypr_'+participant.sid).innerHTML = "Mic off";
                }
                if (track.kind === 'video'){
                  document.getElementById('videoBypr_'+participant.sid).innerHTML = "Video off";
                  console.log('video',track.mediaStreamTrack.muted);
                }
                  
                   //document.getElementById('audio_'+id).appendChild(track.attach());
                });
                 track.on('enabled', () => {
                   if (track.kind === 'audio'){
                    console.log(track);
                    console.log(track.isEnabled);
                     //console.log(track.mediaStreamTrack.muted);
                  document.getElementById('muteBypr_'+participant.sid).innerHTML = "Mic on";
                }
                 if (track.kind === 'video'){
                  document.getElementById('videoBypr_'+participant.sid).innerHTML = "Video on";
                     console.log('video',track.mediaStreamTrack.muted);
                }
                  
                });
                 
            });

            
            participant.on('trackRemoved', trackRemoved);

             $(document).on('click','#muteBypr_'+participant.sid,function(){
               //alert('dfsd');
            });

      
        }

        
     

        function participantDisconnected(participant) {
            console.log('Participant "%s" disconnected', participant.identity);
            //console.log(membersListArray);

            participant.tracks.forEach(publication => {
            if (publication.track) {
             // document.getElementById('video_'+participant.sid).appendChild(publication.track.attach());
              publication.track.detach().forEach( function(element) { element.remove() });
            }
          });
      
            //participant.tracks.forEach(trackRemoved);
           /* if(membersListArray.includes(participant.identity.toString())==true)
              {
                for (var i = membersListArray.length; i--;) {
                    if (membersListArray[i] === participant.identity.toString()) {
                      membersListArray.splice(i, 1);
                    }
                  }
            document.getElementById(participant.sid).remove();
            }*/
            //document.getElementById('partmember_'+participant.identity).style.visibility = 'visible';
            document.getElementById(participant.sid).remove();
           // $('#partmember_'+participant.identity).show();
            document.getElementById('partmember_'+participant.identity).style.display = 'block';
            document.getElementById('member_'+participant.identity).remove();
        }

        /*function handleTrackDisabled(track,sid) {
            track.on('disabled', () => {
              console.log('messages');
            });
          }*/

        function trackAdded(div, track, id) {
           // console.log(track);
           //alert('hi');
                 if (track.kind === 'audio'){
                     if(track.isEnabled==true)
                     document.getElementById('muteBypr_'+id).innerHTML = "Mic on";
                    else
                      document.getElementById('muteBypr_'+id).innerHTML = "Mic off";
                }
                if (track.kind === 'video'){
                  if(track.isEnabled==true)
                  document.getElementById('videoBypr_'+id).innerHTML = "Video on";
                  else
                  document.getElementById('videoBypr_'+id).innerHTML = "Video off";
                  //console.log('video',track.mediaStreamTrack.muted);
                }
             if (track.kind !== 'data') {
            document.getElementById('video_'+id).appendChild(track.attach());
            }
            //$('video').attr('controls', 'controls');//controls
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

    function muteOrUnmuteAllMedia(room, kind, action) {
      console.log(room.participants);
      room.participants.forEach(function(participant) {
        ummuteparticipants(participant,kind,action);
      });
     
    }

    function muteOrUnmuteIndiviualMedia(room, kind, action,sid) {
      console.log('mutesid',sid);
      room.participants.forEach(function(participant,sid) {
        if(participant.sid==sid){
        ummuteparticipants(participant,kind,action);
      }
      });
     /*var myVideo = document.querySelector('#video_'+sid+' video');
     myVideo.setAttribute("controls", "controls");
     console.log(myVideo);*/
    }

    function participantConnectedWorks(participant)
    {

       participant.on('trackSubscribed', track => {
              console.log(`Participant "${participant.identity}" added ${track.kind} Track ${track.sid}`);
              if (track.kind === 'data') {
                track.on('message', data => {
                  console.log(JSON.parse(data));
                  let msg_obj=JSON.parse(data);
                   let partdate = new Date(msg_obj.chat.timestamp).toLocaleTimeString("en-US");
                 $("#message_body").append("<div class='incoming'><div class='bubble'><p>" +msg_obj.chat.sender+ "</p><p>" +msg_obj.chat.message+ "</p><p>" +partdate+ "</p></div></div>");
                   $().toastmessage('showSuccessToast',  msg_obj.chat.sender+' Send New message');
                });
              }

              

            });

       participant.on('trackAdded', function(track) {
                
                track.on('disabled', () => {
                  console.log(participant.sid);
                 // var vid = document.getElementById('video_'+id);
                  // vid.muted = true;
                  if (track.kind === 'audio'){
                     //console.log(track.mediaStreamTrack.enabled);
                     console.log(track);
                     console.log(track.isEnabled);
                  document.getElementById('muteBypr_'+participant.sid).innerHTML = "Mic off";
                }
                if (track.kind === 'video'){
                  document.getElementById('videoBypr_'+participant.sid).innerHTML = "Video off";
                  console.log('video',track.mediaStreamTrack.muted);
                }
                  
                   //document.getElementById('audio_'+id).appendChild(track.attach());
                });
                 track.on('enabled', () => {
                   if (track.kind === 'audio'){
                    console.log(track);
                    console.log(track.isEnabled);
                     //console.log(track.mediaStreamTrack.muted);
                  document.getElementById('muteBypr_'+participant.sid).innerHTML = "Mic on";
                }
                 if (track.kind === 'video'){
                  document.getElementById('videoBypr_'+participant.sid).innerHTML = "Video on";
                     console.log('video',track.mediaStreamTrack.muted);
                }
                  
                });
                  console.log(track);
                 
            });

    }

    function ummuteparticipants(participant ,kind, action)
    {
      
console.log(action);
        participant.audioTracks.forEach(function (track) {

  if (track.kind === 'audio') {
    if (action === 'mute') {
      //console.log('mute',action);
      if(track.mediaStreamTrack.enabled == true){
           track.mediaStreamTrack.enabled = false;
           track.mediaStreamTrack.muted = true;
         }
     }
    else
    {
      //console.log('unmute',action);
        if(track.mediaStreamTrack.enabled == false){
        track.mediaStreamTrack.enabled = true;
         track.mediaStreamTrack.muted = true;
       }
    }
  }
  });
        console.log(participant);
    }


var canvas, ctx, flag = false,
        prevX = 0,
        currX = 0,
        prevY = 0,
        currY = 0,
        dot_flag = false;

    var x = "black",
        y = 2;
    
    function init() {
        canvas = document.getElementById('can');
        ctx = canvas.getContext("2d");
        w = canvas.width;
        h = canvas.height;
    
        canvas.addEventListener("mousemove", function (e) {
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
        }, false);
    }
    
    function color(obj) {
        switch (obj.id) {
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

        sendwhiteboardColor(x);
    
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
        var m = confirm("Want to clear");
        if (m) {
            ctx.clearRect(0, 0, w, h);
            document.getElementById("canvasimg").style.display = "none";
            sendwhiteboardClear();
        }
        
    }
    
    function save() {
        document.getElementById("canvasimg").style.border = "2px solid";
        var dataURL = canvas.toDataURL();
        document.getElementById("canvasimg").src = dataURL;
        document.getElementById("canvasimg").style.display = "inline";
    }
    
    function findxy(res, e) {
      sendwhiteboard(res, e);
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


  function sendwhiteboard(data,e) {
  var messagetrack=JSON.stringify({"whiteboard":{
        data:data,
        cordinats:{
          clientX:e.clientX,
          clientY:e.clientY,

        },
      }});
  console.log(messagetrack);
  console.log(JSON.parse(messagetrack));
  var sdsd=JSON.parse(messagetrack);
  console.log(sdsd.whiteboard.cordinats);
  dataTrackPublished.promise.then(() => dataTrack.send(messagetrack));
 
}

function sendwhiteboardColor(x) {
  var messagetrack=JSON.stringify({"whiteboard":{
        color:x,
      }});
  console.log(messagetrack);
  console.log(JSON.parse(messagetrack));
  var sdsd=JSON.parse(messagetrack);
  console.log(sdsd.whiteboard.color);
  dataTrackPublished.promise.then(() => dataTrack.send(messagetrack));
 
}

function sendwhiteboardClear() {
  var messagetrack=JSON.stringify({"whiteboard":{
        clear:true,
      }});
  console.log(messagetrack);
  var sdsd=JSON.parse(messagetrack);
  console.log(sdsd.whiteboard.clear);
  dataTrackPublished.promise.then(() => dataTrack.send(messagetrack));
 
}

document.querySelector('input').addEventListener('change', function(){
const input = document.getElementById('file_share');

  const file = input.files[0];
  var fileName = document.querySelector('#file_share').value;
  var extension = fileName.split('.').pop();
  var FileType = input.files[0].type;
    console.log('Sending', file);
     var messagetrack=JSON.stringify({"sharefile":{
        name:fileName,
        extension:extension,
        filetype:FileType,
      }});
dataTrackPublished.promise.then(() => dataTrack.send(messagetrack));
const reader = new FileReader();
     reader.readAsDataURL(input.files[0]);
    // We convert the file from Blob to ArrayBuffer
    file.arrayBuffer()
    .then(buffer => {
      /**
       * A chunkSize (in Bytes) is set here
       * I have it set to 16KB
       */
      const chunkSize = 16 * 1024;

      // Keep chunking, and sending the chunks to the other peer
      while(buffer.byteLength) {
        const chunk = buffer.slice(0, chunkSize);
        buffer = buffer.slice(chunkSize, buffer.byteLength);
        
        // Off goes the chunk!
        dataTrackPublished.promise.then(() => dataTrack.send(chunk));
        //peer1.send(chunk);
      }

      // End message to signal that all chunks have been sent
     // peer1.send('Done!');
      dataTrackPublished.promise.then(() => dataTrack.send('Done!'));
       $().toastmessage('showSuccessToast', ' File shared to participants');
    console.log('done');
      });

    

   /* var reader = new FileReader();
    reader.onload = function(){
        var arrayBuffer = this.result;
      console.log(arrayBuffer);
      dataTrackPublished.promise.then(() => dataTrack.send(arrayBuffer));
      alert('hi');
        //document.querySelector('#result').innerHTML = arrayBuffer + '  '+arrayBuffer.byteLength;
        }
    reader.readAsArrayBuffer(this.files[0]);*/
  }, false);    


</script>
@endpush
