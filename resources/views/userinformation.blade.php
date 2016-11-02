@extends('layouts.header')

@section('body')
    <div class="page-banner" ng-controller="MainController">
        <div class="page-usrinf-wrapper">
            <div class="page-content">

                <div class="usrinf-form">
    <?php $oldCode = old('code') ?>
    @if(isset($user_code) || isset($oldCode))
    <form action="./submit_participation" method="post">
        {{ csrf_field() }}
        <div class="form-fields">
        <label for="first_name">voornaam</label>
        <input type="text" id="first_name" name="first_name"  value="{{ old('first_name') }}" />
        </div>

        <div class="form-fields">
        <label for="last_name">achternaam</label>
        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" />
        </div>

        <div class="form-fields">
        <label for="adres">straatnaam</label>
        <input type="text" id="adres" name="adres" value="{{ old('adres') }}" />
        </div>

        <div class="form-fields">
        <label for="housenumber">huisnummer</label>
        <input type="number" id="housenumber" name="housenumber" value="{{ old('housenumber') }}"/>
        </div>

        <div class="form-fields">
        <label for="city">plaats</label>
        <input type="text" id="city" name="city" value="{{ old('city') }}"/>
        </div>

        <div class="form-fields">
        <label for="email"> e-mail </label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"/>
        </div>

        @if(isset($user_image) && isset($user_code))
        <input type="hidden" id="image" name="image" value="{{ $user_image }}"/>
        <input type="hidden" id="code" name="code" value="{{ $user_code }}"/>
        @else
        <input type="hidden" id="image" name="image" value="{{old('image') }}"/>
        <input type="hidden" id="code" name="code" value="{{ old('code') }}"/>
        @endif
        <input id="submit-user" type="submit" name="submit" value="verstuur je deelname"/>

    </form>

                    @if (count($errors) > 0)
                        <div class="error_validation">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
        @else
        <h2>Sorry, you must follow the steps</h2>
        @endif
                </div>

            </div>
        </div>
    </div>
@stop