@extends('layouts.app')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
@endsection


@section('content')
    <div id="root">
        <input type=text name=input v-model=message>

        <p>Message is @{{ message }}</p>
    </div>
@endsection

@section('foot')
    <script>
        let data = {
            message: 'Hello World'
        };

        new Vue({
            el: '#root',
            data: {
                message: 'Hello Vue!'
            }
        });
    </script>
@endsection