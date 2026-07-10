<template>
	<div>
    	<div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
		<div class="my-2">
			<div class="bg-white shadow border-b-8 p-5  py-4">
				<div class="">
		    	    <div class="flex items-center justify-end relative">
		    		<div class="bg-white flex items-center justify-center cursor-pointer">
					    <div class="relative flex items-center w-full justify-end">
						    <p class="text-gray-600 text-xs flex items-center">
								<span class="px-2">
								    <!-- <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve"><g><g><g id="access-time"><path d="M255,0C114.75,0,0,114.75,0,255s114.75,255,255,255s255-114.75,255-255S395.25,0,255,0z M255,459c-112.2,0-204-91.8-204-204S142.8,51,255,51s204,91.8,204,204S367.2,459,255,459z" data-original="#000000" class="active-path" data-old_color="fill-opacity:0.9" fill="#718096"/><polygon points="267.75,127.5 229.5,127.5 229.5,280.5 362.1,362.1 382.5,328.95 267.75,260.1" data-original="#000000" class="active-path" data-old_color="fill-opacity:0.9" fill="#718096"/></g></g></g></svg> -->
								    <svg class="w-4 h-4 fill-current text-gray-600" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve"><g><path d="M30,0C13.458,0,0,13.458,0,30s13.458,30,30,30s30-13.458,30-30S46.542,0,30,0z M30,58C14.561,58,2,45.439,2,30 S14.561,2,30,2s28,12.561,28,28S45.439,58,30,58z"></path><path d="M30,6c-0.552,0-1,0.447-1,1v23H14c-0.552,0-1,0.447-1,1s0.448,1,1,1h16c0.552,0,1-0.447,1-1V7C31,6.447,30.552,6,30,6z"></path></g></svg>
								</span>{{ post.post_created_at }}
							</p>
					    </div>
		            </div>
		    		<div class="bg-white  flex items-center justify-center cursor-pointer">
					    <div class="relative flex items-center w-full justify-end">
						    <p class="text-gray-600 text-sm flex items-center">
								<span class="px-2">
								    <!-- <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 511.999 511.999;" xml:space="preserve" width="512px" height="512px"><g><g> <g><path d="M508.745,246.041c-4.574-6.257-113.557-153.206-252.748-153.206S7.818,239.784,3.249,246.035c-4.332,5.936-4.332,13.987,0,19.923c4.569,6.257,113.557,153.206,252.748,153.206s248.174-146.95,252.748-153.201 C513.083,260.028,513.083,251.971,508.745,246.041z M255.997,385.406c-102.529,0-191.33-97.533-217.617-129.418c26.253-31.913,114.868-129.395,217.617-129.395c102.524,0,191.319,97.516,217.617,129.418    C447.361,287.923,358.746,385.406,255.997,385.406z" data-original="#000000" class="active-path" fill="#fba33a"/></g></g><g><g><path d="M255.997,154.725c-55.842,0-101.275,45.433-101.275,101.275s45.433,101.275,101.275,101.275    s101.275-45.433,101.275-101.275S311.839,154.725,255.997,154.725z M255.997,323.516c-37.23,0-67.516-30.287-67.516-67.516 s30.287-67.516,67.516-67.516s67.516,30.287,67.516,67.516S293.227,323.516,255.997,323.516z" data-original="#000000" class="active-path" fill="#fba33a"/></g></g></g></svg>
								     -->
								    <svg class="w-5 h-5 fill-current text-yellow-500" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 511.992 511.992" style="enable-background:new 0 0 511.992 511.992;" xml:space="preserve"><g><g><path d="M510.096,249.937c-4.032-5.867-100.928-143.275-254.101-143.275C124.56,106.662,7.44,243.281,2.512,249.105 c-3.349,3.968-3.349,9.792,0,13.781C7.44,268.71,124.56,405.329,255.995,405.329S504.549,268.71,509.477,262.886 C512.571,259.217,512.848,253.905,510.096,249.937z M255.995,383.996c-105.365,0-205.547-100.48-230.997-128 c25.408-27.541,125.483-128,230.997-128c123.285,0,210.304,100.331,231.552,127.424 C463.013,282.065,362.256,383.996,255.995,383.996z"></path></g></g><g><g><path d="M255.995,170.662c-47.061,0-85.333,38.272-85.333,85.333s38.272,85.333,85.333,85.333s85.333-38.272,85.333-85.333 S303.056,170.662,255.995,170.662z M255.995,319.996c-35.285,0-64-28.715-64-64s28.715-64,64-64s64,28.715,64,64 S291.28,319.996,255.995,319.996z"></path></g></g></svg>
								</span>{{ post.visibility }}
							</p>
					    </div>
		            </div>
		    		<div @click="showsidebar(id)" class="bg-white rounded-full flex items-center justify-center cursor-pointer ml-3" v-if="post.created_by == auth_id">
		                <svg id="Capa_1" enable-background="new 0 0 515.555 515.555" height="512" viewBox="0 0 515.555 515.555" width="512" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 fill-current text-gray-600"><path d="m303.347 18.875c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path><path d="m303.347 212.209c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path><path d="m303.347 405.541c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path></svg>
		            </div>
		            <div :id="id" class="hidden absolute right-0 bg-white shadow top-0 rounded my-5" v-if="post.created_by == auth_id">
		                <div class="flex flex-col text-xs w-20 my-1">
		                    <a :href="url+'/'+mode+'/classwall/post/edit/'+post.id" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium my-1 lg:my-0 md:my-0" target="_blank">edit</a>
		                    <a href="#" @click="deletePost(post.id)" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium my-1 lg:my-0 md:my-0">delete</a>
		                </div>
		            </div>
		            </div>
	    		</div>
	    			<div class="flex text-xs justify-between flex-col lg:flex-row w-4/5">
					    <div class="my-2 lg:my-0">
					      	<p class="text-gray-800 text-sm" v-html="post.description"></p>
					    </div>   
		    		</div>
	    		<div class="flex my-3 flex-col lg:flex-row md:flex-row" v-if="post.attachments != null">
	      			<div class="leading-loose w-full lg:w-2/3 md:w-2/3" v-for="attachment in post.attachments">  
	        			<img :src="attachment.path" class="w-20 h-20">
	      			</div>
	    		</div>
			    <ul class="list-reset leading-loose my-2 text-xs">
			        <li class="flex py-1 items-center">
			          	<div class="mr-6">
							<div class="flex flex-wrap">
				          		<a href="#" @click="like(1)" v-if="post.like == null || post.like == 0" title="Like">
				          			<svg class="w-4 h-4" version="1.0" xmlns="http://www.w3.org/2000/svg" width="512.000000pt" height="512.000000pt" viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M2425 5106 c-133 -42 -243 -137 -302 -261 -32 -68 -37 -88 -45 -198 -20 -277 -69 -490 -149 -649 -120 -240 -300 -415 -560 -544 l-96 -47 -48 33 c-26 18 -78 43 -114 56 -64 23 -76 24 -392 24 -268 0 -335 -3 -380 -16 -148 -43 -274 -168 -320 -315 -18 -58 -19 -114 -19 -1429 0 -1315 1 -1371 19 -1429 46 -149 173 -273 323 -316 48 -14 106 -16 400 -13 l344 3 75 37 c81 40 165 116 206 186 17 29 30 41 42 38 100 -30 725 -173 857 -197 335 -60 369 -62 1093 -66 753 -5 755 -5 891 63 135 68 232 182 290 341 40 110 53 198 42 282 l-9 65 66 66 c80 80 121 147 166 272 65 181 81 329 49 454 l-15 63 47 58 c61 74 118 196 145 309 30 125 24 289 -14 387 l-26 67 39 61 c70 109 85 170 85 354 0 180 -10 226 -72 328 -95 156 -256 259 -460 292 -116 19 -383 33 -852 45 -223 6 -406 12 -409 14 -2 2 3 42 10 88 8 53 13 177 13 348 0 255 -1 269 -27 370 -97 381 -348 691 -627 774 -65 19 -208 20 -266 2z m222 -338 c144 -77 283 -276 345 -493 21 -74 23 -99 23 -325 0 -283 -6 -324 -85 -579 -28 -90 -48 -166 -45 -169 3 -3 328 -9 723 -13 394 -4 759 -13 810 -18 209 -24 297 -65 345 -163 26 -51 29 -67 28 -149 -1 -122 -13 -162 -64 -213 -68 -67 -112 -77 -381 -83 -260 -7 -266 -8 -266 -83 0 -74 8 -77 246 -82 189 -4 214 -6 259 -27 65 -29 83 -46 111 -106 57 -121 6 -328 -104 -425 -74 -66 -105 -72 -386 -77 -282 -6 -286 -7 -286 -83 0 -74 8 -77 251 -82 231 -6 267 -13 318 -61 62 -58 65 -153 12 -312 -35 -103 -70 -158 -126 -197 -64 -44 -139 -58 -355 -64 -107 -3 -205 -7 -218 -10 -13 -3 -29 -18 -39 -39 -16 -33 -16 -37 0 -70 10 -20 26 -36 39 -39 13 -3 93 -7 178 -9 85 -2 167 -9 182 -15 68 -28 110 -122 89 -200 -33 -122 -92 -199 -179 -234 l-57 -23 -580 0 c-625 1 -738 7 -1055 61 -262 44 -978 215 -1026 245 -68 42 -63 -52 -64 1213 -1 1057 0 1148 16 1181 19 41 55 69 113 90 71 25 221 103 305 159 224 149 381 331 502 581 105 216 157 424 174 691 9 149 21 182 80 221 44 30 111 31 167 1z m-1618 -1593 c44 -26 70 -62 81 -111 12 -52 12 -2556 0 -2608 -11 -49 -37 -85 -81 -111 -32 -19 -52 -20 -309 -20 -259 0 -277 1 -310 21 -48 28 -78 75 -85 137 -3 29 -5 624 -3 1322 3 1249 3 1271 23 1304 25 42 62 70 106 81 19 5 149 8 289 7 235 -2 258 -4 289 -22z"/><path d="M615 941 c-87 -40 -135 -120 -135 -223 1 -142 97 -238 240 -238 144 0 240 96 240 240 0 104 -53 187 -142 224 -52 22 -153 20 -203 -3z m155 -171 c11 -11 20 -33 20 -50 0 -17 -9 -39 -20 -50 -44 -44 -120 -11 -120 52 0 17 7 40 17 50 22 24 77 24 103 -2z"/></g></svg>
				          		</a>
				          		<a href="#" @click="like(0)" v-if="post.like == 1" title="Liked">
				          			<svg class="w-5 h-5" version="1.0" xmlns="http://www.w3.org/2000/svg" width="225.000000pt" height="225.000000pt" viewBox="0 0 225.000000 225.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M1154 1956 c-19 -13 -22 -29 -27 -138 -7 -145 -32 -252 -84 -357 -64 -129 -129 -181 -238 -193 -27 -3 -30 0 -33 30 l-3 32 -245 0 -244 0 0 -525 0 -525 245 0 245 0 0 70 0 70 48 0 c26 0 106 -7 177 -15 349 -40 759 9 805 94 13 25 13 97 0 121 -8 14 -4 21 16 35 56 36 79 112 52 171 -11 24 -10 28 15 46 73 54 76 171 5 223 -19 14 -20 18 -8 23 8 3 29 20 46 38 50 52 55 125 12 187 -38 54 -55 57 -323 57 l-245 0 0 23 c0 12 5 70 12 129 19 178 -9 286 -92 362 -61 55 -100 67 -136 42z m-612 -1413 c28 -25 24 -77 -8 -102 -15 -12 -35 -21 -46 -21 -27 0 -68 45 -68 75 0 55 79 87 122 48z"/></g></svg>
								</a>
								<p class="px-1 font-semibold" v-if="post.like_count > 0">{{ post.like_count }}</p>
				          	</div>
						</div>
			          	<div class="mr-6">
			          		<div class="flex flex-wrap">
			          			<a href="#" @click="dislike(1)" v-if="post.unlike == null || post.unlike == 0" title="Dislike">
			          				<svg class="w-4 h-4" version="1.0" xmlns="http://www.w3.org/2000/svg" width="225.000000pt" height="225.000000pt" viewBox="0 0 225.000000 225.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M1190 2240 c-124 -4 -300 -15 -392 -25 -155 -17 -169 -17 -207 -1 -33 13 -78 16 -224 16 -170 0 -186 -2 -227 -22 -26 -14 -57 -41 -75 -66 l-30 -44 0 -441 c0 -498 -3 -475 78 -541 l39 -31 202 -3 c110 -2 212 1 226 6 22 8 27 4 53 -37 16 -25 57 -75 92 -111 34 -36 83 -97 107 -135 58 -89 157 -286 214 -426 37 -91 44 -119 44 -175 0 -85 15 -136 52 -171 26 -25 37 -28 91 -27 143 1 249 93 278 242 15 81 1 189 -46 344 -35 117 -60 236 -51 245 3 3 54 -3 113 -13 146 -25 356 -25 437 -1 156 48 232 155 208 296 -10 60 -9 67 15 115 39 75 38 164 -3 243 -26 50 -29 64 -21 93 21 82 -6 194 -66 266 -17 21 -26 47 -30 92 -16 150 -121 252 -309 297 -80 20 -282 25 -568 15z m546 -140 c88 -22 147 -58 182 -110 21 -31 23 -43 19 -101 l-6 -66 49 -51 c60 -63 77 -115 60 -176 -25 -87 -24 -95 10 -143 42 -59 50 -115 22 -169 -11 -22 -28 -46 -36 -53 -20 -17 -20 -58 -1 -91 8 -14 14 -45 15 -68 0 -58 -43 -103 -120 -126 -86 -26 -265 -21 -420 10 -120 24 -130 24 -159 10 -77 -40 -81 -158 -11 -391 53 -180 63 -292 31 -357 -23 -48 -80 -88 -125 -88 -32 0 -41 29 -33 102 6 45 1 65 -45 177 -63 157 -171 372 -237 472 -28 42 -79 107 -115 145 -115 122 -109 85 -107 602 1 246 3 447 4 449 5 5 189 24 307 32 69 4 240 9 381 10 226 1 265 -1 335 -19z m-1177 -14 c21 -19 21 -25 21 -432 0 -412 0 -413 -22 -434 -20 -18 -35 -20 -184 -20 -148 0 -163 2 -188 21 l-26 20 0 413 c0 381 1 414 18 428 29 26 41 27 203 25 139 -2 159 -4 178 -21z"/></g></svg>
			          			</a>
			          			<a href="#" @click="dislike(0)" v-if="post.unlike == 1" title="Disliked">
			          				<svg class="w-4 h-4" version="1.0" xmlns="http://www.w3.org/2000/svg" width="215.000000pt" height="234.000000pt" viewBox="0 0 215.000000 234.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,234.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M1160 2219 c-97 -12 -144 -25 -269 -76 -75 -30 -151 -52 -219 -63 -57 -10 -109 -23 -116 -30 -12 -12 -22 -973 -11 -1020 4 -13 73 -89 154 -170 241 -236 367 -402 483 -635 75 -149 88 -165 143 -165 112 0 207 143 207 312 0 130 -44 243 -146 379 -31 40 -52 77 -48 80 4 4 50 3 102 -3 149 -15 413 -5 482 19 137 48 172 112 131 244 -22 72 -20 82 22 143 61 89 60 165 -5 239 -22 25 -40 50 -40 55 0 6 13 39 30 74 46 97 38 156 -31 236 -33 38 -35 54 -13 129 15 51 15 58 -1 99 -28 73 -116 122 -271 149 -95 16 -467 19 -584 4z"/><path d="M52 2174 l-22 -15 0 -585 c0 -384 4 -592 10 -605 10 -17 22 -19 148 -19 94 0 142 4 150 12 16 16 17 1179 2 1209 -10 17 -22 19 -138 19 -99 0 -132 -3 -150 -16z"/></g></svg>
			          			</a>
			          			<p class="px-1 font-semibold" v-if="post.unlike_count > 0">{{ post.unlike_count }}</p>
			          		</div>
			          	</div>
			          	<div class="mr-6">
			          		<a href="#" @click="save(1)" v-if="post.save == null || post.save == 0" title="Save">
			          			<svg class="w-5 h-5" version="1.0" xmlns="http://www.w3.org/2000/svg" width="225.000000pt" height="225.000000pt" viewBox="0 0 225.000000 225.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M455 1930 c-68 -45 -65 -4 -65 -827 l0 -742 23 -26 c29 -33 74 -46 114 -31 17 6 157 118 310 248 153 131 283 238 288 238 6 0 135 -107 288 -238 153 -130 292 -242 310 -248 40 -15 85 -2 115 31 l22 26 0 741 c0 820 3 786 -65 828 -32 20 -48 20 -671 20 -619 0 -640 -1 -669 -20z m1305 -80 c20 -20 20 -31 18 -742 -3 -633 -5 -723 -18 -725 -8 -2 -149 111 -313 251 -163 140 -303 256 -310 259 -6 2 -18 2 -25 -1 -7 -2 -147 -119 -310 -258 -163 -140 -304 -253 -312 -251 -13 2 -15 92 -18 725 -2 711 -2 722 18 742 20 20 33 20 635 20 602 0 615 0 635 -20z"/></g></svg>
			          		</a>
			          		<a href="#" @click="unsave(0)" v-if="post.save == 1" title="Unsave">
			          			<svg class="w-6 h-6" version="1.0" xmlns="http://www.w3.org/2000/svg" width="225.000000pt" height="225.000000pt" viewBox="0 0 225.000000 225.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M645 1728 c-3 -7 -4 -283 -3 -613 l3 -600 23 -3 c20 -3 152 105 437 359 20 18 25 14 238 -172 165 -145 222 -190 240 -187 l22 3 0 610 0 610 -478 3 c-377 2 -479 0 -482 -10z"/></g></svg>
			          		</a>
			          	</div>
			          	<!-- <div class="px-10">
			          		<a href="#">
			          			<svg class="w-5 h-5" version="1.0" xmlns="http://www.w3.org/2000/svg" width="228.000000pt" height="221.000000pt" viewBox="0 0 228.000000 221.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,221.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M1219 2071 l-29 -29 0 -196 0 -196 -89 0 c-473 0 -883 -324 -1012 -798 -15 -56 -22 -120 -26 -232 -6 -172 4 -250 56 -419 l28 -95 37 120 c66 211 153 355 287 477 172 157 408 247 650 247 l69 0 0 -189 c0 -175 1 -190 21 -215 23 -29 73 -35 111 -15 38 21 865 687 882 710 21 31 20 97 -2 131 -15 24 -782 645 -870 704 -46 32 -77 30 -113 -5z m491 -417 c231 -185 420 -340 420 -345 0 -5 -12 -20 -27 -33 -60 -53 -808 -646 -815 -646 -5 0 -8 93 -8 206 l0 206 -187 -4 c-168 -4 -198 -8 -278 -32 -265 -80 -496 -265 -620 -497 l-33 -62 -7 57 c-17 131 22 346 88 488 128 277 382 483 669 542 50 10 129 16 225 16 l148 0 0 220 c0 121 1 220 2 220 2 0 192 -151 423 -336z"/></g></svg>
			          		</a>
			          	</div> -->
			        </li>
			    </ul>
	  		</div>
  		</div>
  		<comment-list :url="this.url" :mode="this.mode" :id="this.id" type="showPost" :auth_id="post.auth_id"></comment-list>

  		<!-- start -->
  	<!-- 	<div class="my-3 bg-white px-3 py-3">
  		<div class="flex">
  			<div class="bg-red-300 w-12 h-12 rounded flex items-center justify-center">
  				<svg class="w-8 h-8 text-white fill-current mx-auto" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m497 416.999h-10v-163c0-24.813-20.187-45-45-45h-139v-73c0-8.284-6.716-15-15-15h-17v-19.689c37.732-9.026 41.302-50.501 18.885-73.102l-23.157-23.693c-5.884-6.02-15.581-6.019-21.462.007l-22.537 23.09c-9.324 9.067-14.456 21.205-14.456 34.206 0 20.015 12.665 34.694 32.728 39.493v19.689h-17c-8.284 0-15 6.716-15 15v73h-139.001c-24.813 0-45 20.187-45 45v163h-10c-8.284 0-15 6.716-15 15v65c0 8.284 6.716 15 15 15h482c8.284 0 15-6.716 15-15v-65c0-8.285-6.716-15.001-15-15.001zm-258.728-355.182c0-4.855 1.933-9.39 5.443-12.768.249-.239.137-.13 12.291-12.583l12.468 12.756c3.49 3.49 5.253 7.955 5.253 12.594 0 2.766 0 11.182-17.728 11.182s-17.727-8.415-17.727-11.181zm.728 89.182h34v58h-34zm-169 88h372c8.271 0 15 6.729 15 15v48.285c-3.435 1.756-6.556 2.715-9 2.715-7.261 0-17.575-7.846-21.394-11.606-2.087-2.087-21.017-20.394-42.606-20.394s-40.52 18.306-42.567 20.354c-3.857 3.8-14.172 11.646-21.433 11.646s-17.575-7.846-21.394-11.606c-2.087-2.087-21.017-20.394-42.606-20.394s-40.52 18.306-42.567 20.354c-3.857 3.8-14.172 11.646-21.433 11.646s-17.575-7.846-21.394-11.606c-2.087-2.087-21.017-20.394-42.606-20.394s-40.52 18.306-42.567 20.354c-3.857 3.8-14.172 11.646-21.433 11.646-2.444 0-5.565-.96-9-2.715v-48.285c0-8.271 6.729-15 15-15zm-15 95.004c3.041.655 6.044.996 9 .996 21.59 0 40.52-18.306 42.567-20.354 3.857-3.8 14.172-11.646 21.433-11.646s17.575 7.846 21.394 11.606c2.087 2.087 21.017 20.394 42.606 20.394s40.52-18.306 42.567-20.354c3.857-3.8 14.172-11.646 21.433-11.646s17.575 7.846 21.394 11.606c2.087 2.087 21.017 20.394 42.606 20.394s40.52-18.306 42.567-20.354c3.857-3.8 14.172-11.646 21.433-11.646s17.575 7.846 21.394 11.606c2.087 2.087 21.017 20.394 42.606 20.394 2.956 0 5.959-.341 9-.996v82.996h-402zm427 147.996h-452v-35h452z"/></g></svg>
  			</div>
  			<div class="px-4 w-10/12">
  				<h2 class="text-lg font-semibold">Today's Birthdays</h2>
  				<div class="border bg-gray-100 px-2 py-2 my-2">
  				    <img src="http://school-plus.test/uploads/male.png" class="w-16 h-16">
  				</div>
  			</div>
  		</div>
  		</div> -->
  		<!-- end -->
	</div>


</template>

<script>
	import { bus } from "../../../app";
	
	export default{
		props:['url', 'mode', 'id', 'auth_id'],
		data() {
			return{
				post:[],
				attachment:'',
				attachment_file:'',
				edit_comments:'',
				comments:'',
				image:'',
				tab:'',
				text:'',
				propsArray:[],
				success:null,
				errors:[],
			}
		},

		methods:
		{
			getData()
			{
				axios.get(this.url+'/'+this.mode+'/classwall/post/showList/'+this.id).then(response => {
					this.post = response.data;
					//console.log(this.post)
				});
			},

			like(val)
			{
			    this.errors=[];
		        this.success=null;

		        let formData=new FormData(); 

		        formData.append('like',val);

		        axios.post(this.url+'/'+this.mode+'/classwall/post/like/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {  
		          	this.success = response.data.success;
		          	window.location.reload();
		        }).catch(error => {
		          	this.errors = error.response.data.errors;
		        });
			},

			dislike(val)
			{
			    this.errors=[];
		        this.success=null;

		        let formData=new FormData(); 

		        formData.append('dislike',val);
		        
		        axios.post(this.url+'/'+this.mode+'/classwall/post/dislike/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {  
		          	this.success = response.data.success;
		          	window.location.reload();
		        }).catch(error => {
		          	this.errors = error.response.data.errors;
		        });
			},

			save(val)
			{
			    this.errors=[];
		        this.success=null;

		        let formData=new FormData(); 

		        formData.append('save',val);

		        axios.post(this.url+'/'+this.mode+'/classwall/post/save/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {  
		          	this.success = response.data.success;
		          	window.location.reload();
		        }).catch(error => {
		          	this.errors = error.response.data.errors;
		        });
			},

			unsave(val)
			{
			    this.errors=[];
		        this.success=null;

		        let formData=new FormData(); 

		        formData.append('save',val);

		        axios.post(this.url+'/'+this.mode+'/classwall/post/unsave/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {  
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

			deletePost(id)
			{
				var thisswal = this;
	      		swal({
			        title: 'Are you sure',
			        text: 'Do you want to delete this Post ?',
			        icon: "info",
			        buttons: [
			          'No',
			          'Yes'
			        ],
			        dangerMode: true,
			      }).then(function(isConfirm) {
			        if (isConfirm) 
			        {
			          axios.delete(thisswal.url+'/'+thisswal.mode+ '/classwall/post/delete/'+ id).then(response => {
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
		},

		created()
		{
			this.getData();
		}
	}
</script>