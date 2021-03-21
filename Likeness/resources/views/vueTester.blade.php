@extends('layouts.app')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <style>
        .width-sm {
            width: 15%;
        }

        .width-lg {
            width: 50%;
        }
    </style>
@endsection


@section('content')
    <div id="root">
        <input type=text name=input v-model=message>

        <p>Message is @{{ message }}</p>

        <div
            @mouseover = "hover = true"
            @mouseleave = "hover = false"
        >
            
        </div>
    </div>
@endsection

@section('foot')
    <script>

        new Vue({
            el: '#root',
            data: {
                message: 'Hello Vue!',
                hover: false,
            },

            methods: {
                onHover() {
                    this.width = 'width-lg'
                }
            }


        });
    </script>
@endsection