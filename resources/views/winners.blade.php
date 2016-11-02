@extends('layouts.header')

@section('body')

    <div class="page-banner">
        <div class="page-wrapper">
            <div class="page-content">
                <h1>Winnaars</h1>
                <table class="table_winners" style="width:100%">
                      <tr>
                            <th>voornaam</th>
                            <th>achternaam</th>
                            <th>tijd</th>
                          </tr>
@foreach($winners as $winner)
    <tr>
        <th>{{$winner->first_name}}</th>
        <th>{{$winner->last_name}}</th>
        <th>{{$winner->created_at}}</th>
    </tr>
    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop