<!-- <!DOCTYPE html> -->
<html lang="en">
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500&family=IBM+Plex+Sans:wght@500;600;700&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="font-primary">
        <div id="app">
            <div class="w-full h-full overflow-auto">
                <div class="bg-white shadow py-3">
                    <div class="container mx-auto px-3 lg:px-0">
                        <div>
                            <!-- <a href="{{ url('/') }}">
                                <img src="{{ asset('images/gego_k12.png') }}" class="inline-block" style="height:55px;" alt="GegoK12 Logo">
                            </a> -->
                        </div>
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
        <!-- Scripts -->
        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{ mix('js/vendor.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="{{ asset('js/custom.js') }}" ></script>
        @stack('scripts')
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script>
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        $(".next").click(function(){
            if(animating) return false;
            animating = true;
  
            current_fs = $(this).parent();
            next_fs = $(this).parent().next();
  
            //activate next step on progressbar using the index of next_fs
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
  
            //show the next fieldset
            next_fs.show(); 
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function(now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = (now * 50)+"%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'transform': 'scale('+scale+')',
                        'position': 'absolute'
                    });
                    next_fs.css({'left': left, 'opacity': opacity});
                }, 
                duration: 800, 
                complete: function(){
                    current_fs.hide();
                    animating = false;
                }, 
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });

        $(".previous").click(function(){
            if(animating) return false;
            animating = true;
  
            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();
  
            //de-activate current step on progressbar
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
  
            //show the previous fieldset
            previous_fs.show(); 
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function(now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = ((1-now) * 50)+"%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({'left': left});
                    previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
                }, 
                duration: 800, 
                complete: function(){
                    current_fs.hide();
                    animating = false;
                }, 
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });

        $(".submit").click(function(){
            return false;
        });

        function readURL(input) 
        {
            if (input.files && input.files[0]) 
            {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <style>
        /*form styles*/
        #msform {
            /*width: 400px;*/
            margin: 50px auto;
            position: relative;
        }
        #msform fieldset {
            background: white;
            border: 0 none;
            border-radius: 3px;
            padding: 20px 30px;
            box-sizing: border-box;
            width: 100%;
            /*  margin: 0 10%;*/
  
            /*stacking fieldsets above each other*/
            position: relative;
        }

        /*Hide all except first fieldset*/
        #msform fieldset:not(:first-of-type) {
            display: none;
        }

        /*inputs*/
        #msform input, #msform textarea {
            /*padding: 8px 10px;
            border-radius: 3px;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
            color: #2C3E50;*/
            font-size: 13px;
            border-radius: 0.25rem;
        }

        /*buttons*/
        #msform .action-button {
            width: 80px;
            background: #3492e2;
            /*font-weight: bold;*/
            color: white;
            border: 0 none;
            border-radius: 0.25rem;
            cursor: pointer;
            padding: 7px 5px;
        }

        #msform .action-button:hover, #msform .action-button:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #3492e2;
        }

        /*headings*/
        .fs-title {
            font-size: 15px;
            text-transform: uppercase;
            color: #2C3E50;
            margin-bottom: 10px;
        }

        .fs-subtitle {
            font-weight: normal;
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }

        /*progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            /*CSS counters to number the steps*/
            counter-reset: step;
            text-align: center;
        }

        #progressbar li {
            list-style-type: none;
            /*  color: white;*/
            text-transform: uppercase;
            font-size: 9px;
            width: calc(100% / 7);
            float: left;
            position: relative;
        }

        #progressbar li:before {
            content: counter(step);
            counter-increment: step;
            width: 20px;
            line-height: 20px;
            display: block;
            font-size: 10px;
            color: #333;
            background: #bfdbfe;
            border-radius: 3px;
            margin: 0 auto 5px auto;
        }

        /*progressbar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: #dbeafe;
            position: absolute;
            left: -50%;
            top: 9px;
            z-index: -1; /*put it behind the numbers*/
        }

        #progressbar li:first-child:after {
            /*connector not needed before the first step*/
            content: none;
        }

        /*marking active/completed steps blue*/
        /*The number of the step and the connector before it = blue*/
        #progressbar li.active:before,  #progressbar li.active:after{
            background: #3492e2;
            color: white;
        }

        @media(max-width: 500px) {
            #progressbar li {
                font-size: 7px;
            }

            #msform fieldset {
                width: 90%;
                margin: auto;
            }
        }
        /*avatar img*/
        .student-avatar {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            opacity: 0;
        }
        .student-img {
            width: 100%;
            height: 173px;
        }
        /*avatar img*/
    </style>
</html>