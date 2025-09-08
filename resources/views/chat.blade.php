<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title> 
     @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])      
        @endif
</head>
<body> 
    <div style="max-width: 600px; margin: 30px auto;">
        <div style="background: #f7f7f7; border-radius: 10px; padding: 20px; min-height: 300px;">
            @foreach($messages as $message)
                <div style="margin-bottom: 15px; display: flex; {{ $message->sender_id == auth()->id() ? 'justify-content: flex-end;' : 'justify-content: flex-start;' }}">
                    <div style="
                        max-width: 70%;
                        padding: 12px 18px;
                        border-radius: 18px;
                        background: {{ $message->sender_id == auth()->id() ? '#007bff' : '#e4e6eb' }};
                        color: {{ $message->sender_id == auth()->id() ? '#fff' : '#333' }};
                        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
                        ">
                        <div style="font-size: 15px;">{{ $message->message }}</div>
                        <div style="font-size: 11px; color: #888; margin-top: 5px; text-align: right;">
                            {{ $message->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
        <form action="{{route("message.store" , $reciver->id)}}" method="POST"> 
            @csrf
            <div style="display: flex; gap: 10px; align-items: center;">
                <input 
                    type="text" 
                    name="message" 
                    placeholder="Type your message..." 
                    required 
                    style="flex: 1; padding: 10px; border-radius: 20px; border: 1px solid #ccc;"
                >
                <button 
                    type="submit" 
                    style="padding: 10px 20px; border-radius: 20px; border: none; background-color: #007bff; color: #fff; cursor: pointer;"
                >
                    Send
                </button>
            </div>
        </form> 
      <script>  
const userId = {{ auth()->id() }};
document.addEventListener('DOMContentLoaded', function () {
    console.log('Trying to subscribe to channel: test.' + userId);
    
    Echo.private('test.' + userId)
        .listen('testEvenet', (e) => {
            console.log('Message received:', e);
        })
        .error((error) => {
            console.log('Channel error:', error);
        });
});
</script>
</body>
</html>