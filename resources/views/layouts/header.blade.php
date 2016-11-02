<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CocaCola winactie!</title>

    <script type="text/javascript" src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/angular.min.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.7/angular-resource.min.js"></script>
    <link href="{{ asset('css/homepage.css') }}" rel="stylesheet">

</head>
<body class="homepage-body" ng-app="mainApp">

<div class="home_btn">
    <p><a href="./">Home</a></p>
</div>
@yield('body')

<script type="text/javascript" src="{{asset('js/main.js')}}"></script>

</body>
</html>

