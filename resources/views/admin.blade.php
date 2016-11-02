@extends('layouts.header')

@section('body')

    <div class="page-banner">
        <div class="page-wrapper admin_wrapper">
            <div class="page-content">
                <h2>Bij active = 1 kunnen deze personen deelnemen aan de wedstrijd</h2>
                <form action="./delete_users" method="post">
                    {{ csrf_field() }}
                <table class="table_winners" style="width:80%">
                    <tr class="table_head">
                        <th>id</th>
                        <th>voornaam</th>
                        <th>achternaam</th>
                        <th>email</th>
                        <th>active</th>
                        <th>delete</th>
                    </tr>
                    @foreach($users as $user)
                        <tr>
                            <th>{{$user->id}}</th>
                            <th>{{$user->first_name}}</th>
                            <th>{{$user->last_name}}</th>
                            <th>{{$user->email}}</th>
                            <th>{{$user->active}}</th>
                           <th> <input type="checkbox" name="selectedUsers[]" value="{{$user->id}}"/></th>
                        </tr>
                    @endforeach
                </table>
                <input type="submit" class='admin_buttons' name="delete" value="Verwijderen uit winactie"/>
                <input type="submit" class='admin_buttons' name="add" value="Toevoegen in winactie"/>
                <input type="submit" class='admin_buttons harddelete' name="hard_delete" value="Hard delete (dev)"/>

                </form>
            </div>
        </div>
    </div>
@stop