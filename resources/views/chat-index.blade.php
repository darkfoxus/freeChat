<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"> 
        <title>FreeChat</title>

        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <link rel="stylesheet" href="/chat-style.css">
    </head>
    <body>
        <div class="chat">
            <div class="top">
                <img src="/img/avatar1.png" alt="Avatar">
                <div>
                    <p> Ross Edlin </p>
                    <small> Online </small>
                </div>
            </div>

            <div class="messages">
                @include('receive', ['message'=>"hola, bienvenido al chat"])
            </div>

            <div class="bottom">
                <form>
                    <input type="text" id="message" name="message" placeholder="enter message" autocomplete="off">
                    <button type="submit"> </button>
                </form>
            </div>


        </div>

        

    </body>

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

</html> 