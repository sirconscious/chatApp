<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @endif
</head>
<body>

    <div style="max-width: 600px; margin: 30px auto;">
        <div id="chat-messages" style="background: #f7f7f7; border-radius: 10px; padding: 20px; min-height: 300px;">
            @foreach($messages as $message)
                <div style="margin-bottom: 15px; display: flex; {{ $message->senderId == auth()->id() ? 'justify-content: flex-end;' : 'justify-content: flex-start;' }}">
                    <div style="
                        max-width: 70%;
                        padding: 12px 18px;
                        border-radius: 18px;
                        background: {{ $message->senderId == auth()->id() ? '#007bff' : '#e4e6eb' }};
                        color: {{ $message->senderId == auth()->id() ? '#fff' : '#333' }};
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

    <form id="message-form" action="{{route('message.store', $reciver->id)}}" method="POST">
        @csrf
        <div style="display: flex; gap: 10px; align-items: center; max-width: 600px; margin: 0 auto;">
            <input
                id="message-input"
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
        const receiverId = {{ $reciver->id }};
        const chatMessages = document.getElementById('chat-messages');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');

        document.addEventListener('DOMContentLoaded', function () {
            console.log('Trying to subscribe to channel: test.' + userId);
            
            // Listen for incoming messages
            Echo.private('test.' + userId)
                .listen('testEvenet', (e) => {
                    console.log('Message received:', e);
                    addMessageToChat(e.message, false); // false = not from current user
                })
                .error((error) => {
                    console.log('Channel error:', error);
                });

            // Handle form submission with AJAX
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const messageText = messageInput.value.trim();
                if (!messageText) return;

                // Send message via AJAX
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Message sent successfully');
                    // Add message to chat immediately for sender
                    addMessageToChat({
                        message: messageText,
                        created_at: new Date().toISOString()
                    }, true); // true = from current user
                    
                    // Clear input
                    messageInput.value = '';
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                });
            });
        });

        // Function to add message to chat
        function addMessageToChat(message, isFromCurrentUser) {
            const messageDiv = document.createElement('div');
            messageDiv.style.cssText = `
                margin-bottom: 15px; 
                display: flex; 
                ${isFromCurrentUser ? 'justify-content: flex-end;' : 'justify-content: flex-start;'}
            `;
            
            const now = new Date();
            const timeStr = now.getHours().toString().padStart(2, '0') + ':' + 
                           now.getMinutes().toString().padStart(2, '0');
            
            messageDiv.innerHTML = `
                <div style="
                    max-width: 70%;
                    padding: 12px 18px;
                    border-radius: 18px;
                    background: ${isFromCurrentUser ? '#007bff' : '#e4e6eb'};
                    color: ${isFromCurrentUser ? '#fff' : '#333'};
                    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
                ">
                    <div style="font-size: 15px;">${message.message}</div>
                    <div style="font-size: 11px; color: #888; margin-top: 5px; text-align: right;">
                        ${timeStr}
                    </div>
                </div>
            `;
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    </script>
</body>
</html>