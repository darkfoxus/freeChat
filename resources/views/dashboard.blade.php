<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                   


                    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <link rel="stylesheet" href="/chat-style.css">


                    <div class="chat">
            <div class="top">
                <img src="/img/avatar{{Auth::user()->id}}.png" alt="Avatar">
                <div>
                    <p> {{Auth::user()->name}} </p>
                    <small> Online </small>
                </div>
            </div>

            <div class="messages">
                @php
                $message = "hola ".Auth::user()->name.", bienvenido al chat";
                @endphp

                @include('receive', ['message'=>"{$message}"])
            </div>

            <div class="bottom">
                <form>
                    <input type="text" id="message" name="message" placeholder="enter message" autocomplete="off">
                    <button type="submit"> </button>
                </form>
            </div>


        </div>

        

   

    <script>
        const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}',{cluster:'us2'});

        //nos subscribimos al canal general
        const channel = pusher.subscribe('public');

        //recibir mensajes escuchando la implementación observer pattern
        channel.bind('chat', function(data){
            $.post("/receive", {
                _token: '{{csrf_token()}}',
                message: data.message,
            })
                .done (function (res) {
                    $(".messages > .message").last().after(res);
                    $(document).scrollTop($(document).height());
                });
        });

        //enviar mensajes
        $("form").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: "/broadcast",
                method: 'POST',
                headers: {
                    'X-Socket-Id': pusher.connection.socket_id
                },
                data: {
                    _token: '{{csrf_token()}}',
                    message: $("form #message").val(),
                }
            }).done (function (res){
                $(".messages > .message").last().after(res);
                $("form #message").val('');
                $(document).scrollTop($(document).height());
            });

        });
        /*
    */
    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
