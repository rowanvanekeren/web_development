@extends('layouts.header')

@section('body')

<div class="page-banner">
 <div class="page-wrapper">
        <div class="page-content">

            <h1>Win een exclusieve trip naar Ibiza!</h1>
            @if(!$ipExist)
            <h2>voer uw QR code in en doe mee!</h2>
            <button class="page-start-button" onclick="window.location.href='./uploadcode'">Ik doe mee!</button>
            @else
            <h2>Helaas je hebt al eens meegedaan</h2>
            @endif
            <p><a href="./winners">vorige winnaars</a></p>
        </div>
 </div>
</div>
@stop