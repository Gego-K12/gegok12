<template>
	<div>
    	<div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
		<div class="flex flex-wrap">
   			<div class="flex flex-wrap w-full lg:w-1/4 md:w-1/2 md:mr-2 lg:mr-3 my-2" v-for="page in pages">
      			<div class="bg-white shadow border border-grey px-5 py-3 w-full">
	   				<div class="flex flex-wrap lg:flex-row justify-between items-center mb-2">
				        <div class="">
				            <h1 class="admin-h1">{{ page.page_name }}</h1>
				        </div>
				        <div class="relative flex items-center w-1/4 md:w-1/2 justify-end">
				            <div class="flex items-center">
				                <a href="#" @click="showsidebar(page.id)" class="no-underline text-white  flex items-center bg-white rounded-full py-1 justify-center">
				                  	<svg id="Capa_1" enable-background="new 0 0 515.555 515.555" height="512" viewBox="0 0 515.555 515.555" width="512" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 fill-current text-gray-600"><path d="m303.347 18.875c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path><path d="m303.347 212.209c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path><path d="m303.347 405.541c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path></svg>
				                </a> 
				            </div>
				            <div :id="page.id" class="hidden absolute left-0 bg-white shadow right-0 top-0 my-5 rounded">
				                <div class="flex flex-col text-xs w-20 my-1">
				                    <a :href="url+'/'+mode+'/classwall/page/show/'+page.id" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium" target="_blank">view</a>
				                    <a :href="url+'/'+mode+'/classwall/page/edit/'+page.id" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium" target="_blank">edit</a>
				                    <a href="#" @click="deletePage(page.id)" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium">delete</a>
				                </div>
				            </div>
				        </div>
				    </div>
         			<div class="">
		            	<img class="card-img-top  h-40 w-full" :src="page.cover_image">
		        	</div> 
<p class="text-sm text-gray-700 flex items-center py-2" v-html="trim(page.description)"></p>
		          	<ul class="list-reset leading-loose my-2 text-xs">
		          		<li class="flex py-1 items-center justify-between">
		          		<div class="flex items-center">
		          			<div class="mr-6">
								<div class="flex flex-wrap">
			          				<a href="#" @click="like(page.id,1)" v-if="page.like == null || page.like == 0" title="Like">
			          					<svg class="w-4 h-4" version="1.0" xmlns="http://www.w3.org/2000/svg" width="512.000000pt" height="512.000000pt" viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M2425 5106 c-133 -42 -243 -137 -302 -261 -32 -68 -37 -88 -45 -198 -20 -277 -69 -490 -149 -649 -120 -240 -300 -415 -560 -544 l-96 -47 -48 33 c-26 18 -78 43 -114 56 -64 23 -76 24 -392 24 -268 0 -335 -3 -380 -16 -148 -43 -274 -168 -320 -315 -18 -58 -19 -114 -19 -1429 0 -1315 1 -1371 19 -1429 46 -149 173 -273 323 -316 48 -14 106 -16 400 -13 l344 3 75 37 c81 40 165 116 206 186 17 29 30 41 42 38 100 -30 725 -173 857 -197 335 -60 369 -62 1093 -66 753 -5 755 -5 891 63 135 68 232 182 290 341 40 110 53 198 42 282 l-9 65 66 66 c80 80 121 147 166 272 65 181 81 329 49 454 l-15 63 47 58 c61 74 118 196 145 309 30 125 24 289 -14 387 l-26 67 39 61 c70 109 85 170 85 354 0 180 -10 226 -72 328 -95 156 -256 259 -460 292 -116 19 -383 33 -852 45 -223 6 -406 12 -409 14 -2 2 3 42 10 88 8 53 13 177 13 348 0 255 -1 269 -27 370 -97 381 -348 691 -627 774 -65 19 -208 20 -266 2z m222 -338 c144 -77 283 -276 345 -493 21 -74 23 -99 23 -325 0 -283 -6 -324 -85 -579 -28 -90 -48 -166 -45 -169 3 -3 328 -9 723 -13 394 -4 759 -13 810 -18 209 -24 297 -65 345 -163 26 -51 29 -67 28 -149 -1 -122 -13 -162 -64 -213 -68 -67 -112 -77 -381 -83 -260 -7 -266 -8 -266 -83 0 -74 8 -77 246 -82 189 -4 214 -6 259 -27 65 -29 83 -46 111 -106 57 -121 6 -328 -104 -425 -74 -66 -105 -72 -386 -77 -282 -6 -286 -7 -286 -83 0 -74 8 -77 251 -82 231 -6 267 -13 318 -61 62 -58 65 -153 12 -312 -35 -103 -70 -158 -126 -197 -64 -44 -139 -58 -355 -64 -107 -3 -205 -7 -218 -10 -13 -3 -29 -18 -39 -39 -16 -33 -16 -37 0 -70 10 -20 26 -36 39 -39 13 -3 93 -7 178 -9 85 -2 167 -9 182 -15 68 -28 110 -122 89 -200 -33 -122 -92 -199 -179 -234 l-57 -23 -580 0 c-625 1 -738 7 -1055 61 -262 44 -978 215 -1026 245 -68 42 -63 -52 -64 1213 -1 1057 0 1148 16 1181 19 41 55 69 113 90 71 25 221 103 305 159 224 149 381 331 502 581 105 216 157 424 174 691 9 149 21 182 80 221 44 30 111 31 167 1z m-1618 -1593 c44 -26 70 -62 81 -111 12 -52 12 -2556 0 -2608 -11 -49 -37 -85 -81 -111 -32 -19 -52 -20 -309 -20 -259 0 -277 1 -310 21 -48 28 -78 75 -85 137 -3 29 -5 624 -3 1322 3 1249 3 1271 23 1304 25 42 62 70 106 81 19 5 149 8 289 7 235 -2 258 -4 289 -22z"/><path d="M615 941 c-87 -40 -135 -120 -135 -223 1 -142 97 -238 240 -238 144 0 240 96 240 240 0 104 -53 187 -142 224 -52 22 -153 20 -203 -3z m155 -171 c11 -11 20 -33 20 -50 0 -17 -9 -39 -20 -50 -44 -44 -120 -11 -120 52 0 17 7 40 17 50 22 24 77 24 103 -2z"/></g></svg>
			          				</a>
			          				<a href="#" @click="like(page.id,0)" v-if="page.like == 1" title="Liked">
			          					<svg class="w-4 h-4" version="1.0" xmlns="http://www.w3.org/2000/svg" width="225.000000pt" height="225.000000pt" viewBox="0 0 225.000000 225.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M1154 1956 c-19 -13 -22 -29 -27 -138 -7 -145 -32 -252 -84 -357 -64 -129 -129 -181 -238 -193 -27 -3 -30 0 -33 30 l-3 32 -245 0 -244 0 0 -525 0 -525 245 0 245 0 0 70 0 70 48 0 c26 0 106 -7 177 -15 349 -40 759 9 805 94 13 25 13 97 0 121 -8 14 -4 21 16 35 56 36 79 112 52 171 -11 24 -10 28 15 46 73 54 76 171 5 223 -19 14 -20 18 -8 23 8 3 29 20 46 38 50 52 55 125 12 187 -38 54 -55 57 -323 57 l-245 0 0 23 c0 12 5 70 12 129 19 178 -9 286 -92 362 -61 55 -100 67 -136 42z m-612 -1413 c28 -25 24 -77 -8 -102 -15 -12 -35 -21 -46 -21 -27 0 -68 45 -68 75 0 55 79 87 122 48z"/></g></svg>
									</a>
									<p class="px-2 font-semibold" v-if="page.like_count > 0">{{ page.like_count }}</p>
				          		</div>
		          			</div>
		          			<div class="mr-6">
								<div class="flex flex-wrap">
			          				<a href="#" @click="dislike(page.id,1)" v-if="page.dislike == null || page.dislike == 0" title="Dislike">
			          					<svg class="w-4 h-4" version="1.0" xmlns="http://www.w3.org/2000/svg" width="225.000000pt" height="225.000000pt" viewBox="0 0 225.000000 225.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M1190 2240 c-124 -4 -300 -15 -392 -25 -155 -17 -169 -17 -207 -1 -33 13 -78 16 -224 16 -170 0 -186 -2 -227 -22 -26 -14 -57 -41 -75 -66 l-30 -44 0 -441 c0 -498 -3 -475 78 -541 l39 -31 202 -3 c110 -2 212 1 226 6 22 8 27 4 53 -37 16 -25 57 -75 92 -111 34 -36 83 -97 107 -135 58 -89 157 -286 214 -426 37 -91 44 -119 44 -175 0 -85 15 -136 52 -171 26 -25 37 -28 91 -27 143 1 249 93 278 242 15 81 1 189 -46 344 -35 117 -60 236 -51 245 3 3 54 -3 113 -13 146 -25 356 -25 437 -1 156 48 232 155 208 296 -10 60 -9 67 15 115 39 75 38 164 -3 243 -26 50 -29 64 -21 93 21 82 -6 194 -66 266 -17 21 -26 47 -30 92 -16 150 -121 252 -309 297 -80 20 -282 25 -568 15z m546 -140 c88 -22 147 -58 182 -110 21 -31 23 -43 19 -101 l-6 -66 49 -51 c60 -63 77 -115 60 -176 -25 -87 -24 -95 10 -143 42 -59 50 -115 22 -169 -11 -22 -28 -46 -36 -53 -20 -17 -20 -58 -1 -91 8 -14 14 -45 15 -68 0 -58 -43 -103 -120 -126 -86 -26 -265 -21 -420 10 -120 24 -130 24 -159 10 -77 -40 -81 -158 -11 -391 53 -180 63 -292 31 -357 -23 -48 -80 -88 -125 -88 -32 0 -41 29 -33 102 6 45 1 65 -45 177 -63 157 -171 372 -237 472 -28 42 -79 107 -115 145 -115 122 -109 85 -107 602 1 246 3 447 4 449 5 5 189 24 307 32 69 4 240 9 381 10 226 1 265 -1 335 -19z m-1177 -14 c21 -19 21 -25 21 -432 0 -412 0 -413 -22 -434 -20 -18 -35 -20 -184 -20 -148 0 -163 2 -188 21 l-26 20 0 413 c0 381 1 414 18 428 29 26 41 27 203 25 139 -2 159 -4 178 -21z"/></g></svg>
			          				</a>
			          				<a href="#" @click="dislike(page.id,0)" v-if="page.dislike == 1" title="Disliked">
			          					<svg class="w-4 h-4" version="1.0" xmlns="http://www.w3.org/2000/svg" width="215.000000pt" height="234.000000pt" viewBox="0 0 215.000000 234.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,234.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M1160 2219 c-97 -12 -144 -25 -269 -76 -75 -30 -151 -52 -219 -63 -57 -10 -109 -23 -116 -30 -12 -12 -22 -973 -11 -1020 4 -13 73 -89 154 -170 241 -236 367 -402 483 -635 75 -149 88 -165 143 -165 112 0 207 143 207 312 0 130 -44 243 -146 379 -31 40 -52 77 -48 80 4 4 50 3 102 -3 149 -15 413 -5 482 19 137 48 172 112 131 244 -22 72 -20 82 22 143 61 89 60 165 -5 239 -22 25 -40 50 -40 55 0 6 13 39 30 74 46 97 38 156 -31 236 -33 38 -35 54 -13 129 15 51 15 58 -1 99 -28 73 -116 122 -271 149 -95 16 -467 19 -584 4z"/><path d="M52 2174 l-22 -15 0 -585 c0 -384 4 -592 10 -605 10 -17 22 -19 148 -19 94 0 142 4 150 12 16 16 17 1179 2 1209 -10 17 -22 19 -138 19 -99 0 -132 -3 -150 -16z"/></g></svg>
			          				</a>
									<p class="px-2 font-semibold" v-if="page.unlike_count > 0">{{ page.unlike_count }}</p>
				          		</div>
		          			</div>
		          			</div>
		          			<div>
		          				<a href="#" class="no-underline text-white px-4 mx-1 flex items-center bg-red-600 justify-center rounded" v-if="page.is_following == 0 || page.is_following == null" @click="following(page.id,1)" title="Follow">Follow</a>
		          				<a href="#" class="no-underline text-white px-4 mx-1 flex items-center custom-green justify-center rounded" v-if="page.is_following == 1" @click="following(page.id,0)" title="Following">Following</a>
		          			</div>
		          		</li>
		          	</ul> 
      			</div>
   			</div>
	  		<div class="bg-white shadow border-b-8 p-4 w-full" v-if="Object.keys(pages).length == 0">
	  			<p class="flex items-center">No Records Found</p>
	  		</div>
		</div>
	</div>
</template>

<script>
	export default{
		props:['url' , 'mode'],
		data() {
			return{
				pages:[],
				errors:[],
				success:null,
			}
		},

		methods:
		{
			getData()
			{
				axios.get(this.url+'/'+this.mode+'/classwall/page/list').then(response => {
					this.pages = response.data.data;
				});
			},

		    trim(string) 
		    {
		      return string.substring(0,140) + '...';
		    },

			deletePage(id)
			{
				var thisswal = this;
	      		swal({
			        title: 'Are you sure',
			        text: 'Do you want to delete this Page ?',
			        icon: "info",
			        buttons: [
			          'No',
			          'Yes'
			        ],
			        dangerMode: true,
			      }).then(function(isConfirm) {
			        if (isConfirm) 
			        {
			          axios.delete(thisswal.url+'/'+thisswal.mode+ '/classwall/page/delete/'+ id).then(response => {
			            thisswal.success = response.data.success;
			            window.location.reload();
			          }); 
			        }
			        else 
			        {
			          swal("Cancelled");
			        }
			    });
			},

			following(id,val)
			{
			    this.errors=[];
		        this.success=null;

		        let formData=new FormData(); 

		        formData.append('is_following',val);

		        axios.post(this.url+'/'+this.mode+'/classwall/page/follow/'+id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {  
		          	this.success = response.data.success;
		          	window.location.reload();
		        }).catch(error => {
		          	this.errors = error.response.data.errors;
		        });
			},

			like(id,val)
			{
			    this.errors=[];
		        this.success=null;

		        let formData=new FormData(); 

		        formData.append('like',val);

		        axios.post(this.url+'/'+this.mode+'/classwall/page/like/'+id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {  
		          	this.success = response.data.success;
		          	window.location.reload();
		        }).catch(error => {
		          	this.errors = error.response.data.errors;
		        });
			},

			dislike(id,val)
			{
			    this.errors=[];
		        this.success=null;

		        let formData=new FormData(); 

		        formData.append('dislike',val);

		        axios.post(this.url+'/'+this.mode+'/classwall/page/dislike/'+id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {  
		          	this.success = response.data.success;
		          	window.location.reload();
		        }).catch(error => {
		          	this.errors = error.response.data.errors;
		        });
			},

			showsidebar(id)
			{
			    if($('#'+id).hasClass('hidden'))
			    {
			      $('#'+id).removeClass('hidden').addClass('block');
			    }
			    else
			    {
			      $('#'+id).removeClass('block').addClass('hidden');
			    }
			},
		},

		created()
		{
			this.getData();
		}
	}
</script>