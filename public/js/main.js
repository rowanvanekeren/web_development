(function() {



    var app = angular.module("mainApp",[]);

    app.controller('MainController',['$rootScope','$scope','$http', '$location', function($rootScope,$scope,$http,$location,userinfo) {

        var user_code;
        var data;
        var test;
        $scope.initPage = function(){
          $('.page-wrapper-anim').addClass('page-upload-wrapper');
            $('.page-usrinf-wrapper').addClass('page-usrinf-wrapper-anim');
            $('.enable-video-start').addClass('enable-video-end');
            $('.enable-video-end').removeClass('enable-video-start');
            $('#canvas').hide();
            $('#startbutton').hide();
            $('#resetButton').hide();
            $('#scanqrbutton').hide();
            $('.loading').hide();
        };

        $scope.initPage();
        $scope.start_capture = function(){
            $('#startbutton').show();
            $('.enable-video-start').removeClass('enable-video-end');
            $('.enable-video-end').addClass('enable-video-start');
            $scope.initWebcamCapture();
        };
        $scope.initWebcamCapture = function(){
            var streaming = false,
                video        = document.querySelector('#video'),
                canvas       = document.querySelector('#canvas'),
                photo        = document.querySelector('#photo'),
                startbutton  = document.querySelector('#startbutton'),
                width = 500,
                height = 500;

            navigator.getMedia = ( navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);

            navigator.getMedia(
                {
                    video: true,
                    audio: false
                },
                function(stream) {
                    if (navigator.mozGetUserMedia) {
                        video.mozSrcObject = stream;
                    } else {
                        var vendorURL = window.URL || window.webkitURL;
                        video.src = vendorURL.createObjectURL(stream);
                    }
                    video.play();
                },
                function(err) {
                    console.log("An error occured! " + err);
                }
            );

            video.addEventListener('canplay', function(ev){
                if (!streaming) {
                    height = video.videoHeight / (video.videoWidth/width);
                    video.setAttribute('width', width);
                    video.setAttribute('height', height);
                    canvas.setAttribute('width', width);
                    canvas.setAttribute('height', height);
                    streaming = true;
                }
            }, false);

            function takepicture() {
                $('#scanqrbutton').show();
                $('#startbutton').hide();
                $('#resetButton').show();
                $('#canvas').show();
                $('#video').hide();
                canvas.width = width;
                canvas.height = height;
                test = canvas.getContext('2d').drawImage(video, 0, 0, width, height);
                data = canvas.toDataURL('image/png');


                photo.setAttribute('src', data);

            }

            startbutton.addEventListener('click', function(ev){
                takepicture();
                ev.preventDefault();
            }, false);
        };

        $scope.procesImage = function(){
            $('.loading').show();
            $('.loading').css('background-image',"url(./images/loading/ajax-loader.gif)");
            $('#startbutton').hide();
            $('#resetButton').hide();
            $('#scanqrbutton').hide();
            $http.post('./checkqr',{

                image: data

            }).success(function(data){
                $scope.text = data;
                if(data[0] == "success"){

                    $scope.user_code_info = data;
                    $('.loading').css('background-image','url(./images/icons/approved.png)')
                    setTimeout(function(){
                        $scope.code_reqognized = true;
                        $scope.$apply();
                        $('.loading').hide();
                    }, 1000);
                }
                else if(data[0] == "fail"){
                    $scope.code_reqognized = false;
                    $scope.user_code_info = "";

                    $('.loading').css('background-image','url(./images/icons/fail.png)');
                    setTimeout(function(){
                        $('#resetButton').show();
                        $('.loading').hide();
                    }, 1000);
                }

            }).error(function(data){
                $scope.text = data;
            });
        };

        $scope.submit_page = function(){

            console.log('check function');

            var code_recoqnized = $scope.code_reqognized;
            var user_code_info = $scope.user_code_info;
            console.log(user_code_info);

            if(code_recoqnized != "" &&
            code_recoqnized != false &&
            user_code_info != ""
            ){

                if(user_code_info[0] == "success"){
                    $scope.code_reqognized = true;

                    //$scope.user_code_info = data;
                   $rootScope.userinfo = user_code_info;
                    $location.url('/userinformation');
                }
                else if(user_code_info[0] == "fail"){
                    $scope.code_reqognized = false;
                    $scope.userinfo = "";
                }
/*                $http.post('./submit_user',{

                    user_code_info: user_code_info

                }).success(function(data){
                    //$scope.text = data;
                    if(data[0] == "success"){
                        $scope.code_reqognized = true;
                        $scope.user_code_info = data;
                    }
                    else if(data[0] == "fail"){
                        $scope.code_reqognized = false;
                        $scope.user_code_info = "";
                    }

                }).error(function(data){
                    //$scope.text = data;
                });*/
            }
        };

        $scope.resetVideo = function(){
            $('#video').show();
            $('#startbutton').show();
            $('#resetButton').hide();
            $('#canvas').hide();
            $('#scanqrbutton').hide();
            $('.loading').hide();

        };

    }]);
})();

