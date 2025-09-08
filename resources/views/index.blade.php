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
        <div class="container mt-5">
            <h2 class="mb-4">Users</h2>
            <div class="row">
                @foreach($users as $user)
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $user->name }}</h5>
                                <p class="card-text">{{ $user->email }}</p>
                                <a href="{{route("chat",$user->id)}}" class="btn btn-primary">Chat</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
</body>
</html>