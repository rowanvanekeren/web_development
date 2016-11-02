@extends('layouts.header')

@section('body')

    <div class="page-banner" ng-controller="MainController">
        <div class="enable-video-start">
            <button class="start-capture-btn" ng-click="start_capture()">start video</button>
        </div>
        <div class="page-wrapper-anim">
            <div class="page-content">

       <div class="capture-screen">

        <video id="video"></video>
        <canvas id="canvas"></canvas>
       </div>
                <button id="startbutton">Maak foto</button>
                <button id="resetButton" ng-click="resetVideo()">Reset</button>
                <button id="scanqrbutton" ng-click="procesImage()">Scan QR</button>
                <div class="loading"></div>


        <form ng-show="code_reqognized" action="./submit_user" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="successorfail" id="userinfo" value="@{{ user_code_info[0] }}"/>
            <input type="hidden" name="user_code" id="userinfo" value="@{{ user_code_info[1] }}"/>
            <input type="hidden" name="user_image" id="userinfo" value="@{{ user_code_info[2] }}"/>
            <input type="submit" id="nextstep" value="volgende stap"/>
        </form>


            </div>
        </div>
    </div>
@endsection