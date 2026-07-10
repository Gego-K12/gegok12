@extends('layouts.video')
@section('content')
<div class="">
     <div class="video-wrapper" id="media-div">
     </div>
 </div>
@endsection

@push('scripts')
<link href="{{url('css/fullcalendar/jquery.toastmessage.css')}}" rel="stylesheet" type="text/css">
<script src="{{url('/js/jquery.toastmessage.js')}}"></script> 
<script src="//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js"></script>
<script type="text/javascript">
	document.addEventListener('contextmenu', event => event.preventDefault());
    Twilio.Video.createLocalTracks({
       audio: true,
       video: { height: 380, frameRate: 24, width: 720 }
    }).catch(function(error) {
    	window.location.href = '{{url("video")}}';
    }).then(function(localTracks) {
       return Twilio.Video.connect('{{ $accessToken }}', {
            name: '{{ $roomName }}',
            tracks: localTracks,
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

       $(document).on('click','#diable_audio',function(){
       		var check  = $(this).data('type');
       		if(check=='mute'){
       			$(this).data('type','unmute');
       		}else{
       			$(this).data('type','mute');
       		}
       		muteOrUnmuteYourMedia(room, 'audio', check);
       });

       $(document).on('click','#diable_video',function(){
       	    var check  = $(this).data('type');
       		if(check=='mute'){
       			$(this).data('type','unmute');
       		}else{
       			$(this).data('type','mute');
       		}
       	 muteOrUnmuteYourMedia(room, 'video', check);
       });

       $(document).on('click','#leave_conference',function(){
       	 	 room.localParticipant.tracks.forEach(track => {
       	 	 	track.stop();
			    const attachedElements = track.detach();
			    attachedElements.forEach(element => element.remove());
			 });
			 document.getElementById(room.localParticipant.sid).remove();
			 room.disconnect();
			 window.location.href = '{{url("video-conference")}}';
       });

    });
    // additional functions will be added after this point
    function participantConnected(participant) {
       console.log('Participant "%s" connected', participant.identity);
       // <div style='clear:both'>" +participant.identity+ "</div><div><a href='javascript:void(0);' data-type='mute'id='diable_audio'>Audio</a> | <a href='javascript:void(0);' data-type='mute' id='diable_video'>Video</a> | <a href='javascript:void(0);' id='leave_conference'>Leave</a></div>

       const div = document.createElement('div');
       div.id = participant.sid;
       div.setAttribute("style", "float: left; margin: 10px;");
       div.innerHTML = "<div><div class='bg-gray-200 border border-white relative'><div class='absolute bottom-0'><p class='bg-gray-100 px-2 py-1 text-gray-700 text-xs'>" +participant.identity+ "</p></div><div class='video-space' id='video_"+participant.sid+"'></div></div></div>";

       document.getElementById('media-div').appendChild(div);

       participant.tracks.forEach(function(track) {
          trackAdded(div, track, participant.sid)
       });

       participant.on('trackAdded', function(track) {
           trackAdded(div, track, participant.sid)
       });

       participant.on('trackRemoved', trackRemoved);
      
    }

    function participantDisconnected(participant) {
       console.log('Participant "%s" disconnected', participant.identity);

       participant.tracks.forEach(trackRemoved);
       document.getElementById(participant.sid).remove();
    }

    function trackAdded(div, track, id) {
      document.getElementById('video_'+id).appendChild(track.attach());
       // div.appendChild(track.attach('#video_'+id));
       // $('#video_'+id).append(track.attach());
       // var video = div.getElementsByTagName("video")[0];
       // if (video) {
       //     video.setAttribute("style", "max-width:300px;");
       // }
    }

    function trackRemoved(track) {
       track.detach().forEach( function(element) { element.remove() });
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
</script>
@endpush