<?php
/**
 * Created by PhpStorm.
 * User: David Pratt Jr
 * Date: 2/17/2021
 * Time: 11:29 PM
 */
?>


@extends ('layouts.app')


@section('head')

@endsection



@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Search') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('search') }}">
                            @csrf

                            <div class="mb-3 row">

                                <div class="col-md-3"></div>

                                <div class="col-md-6">
                                    <input id="search" type="search" class="form-control @error('search') is-invalid @enderror" name="search" value="{{ old('search') }}" required autofocus>

                                    @error('search')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-3"></div>

                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-8 offset-md-5">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Search') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection