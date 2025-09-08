<?php

namespace App\Http\Controllers;

use App\Events\testEvenet;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{   
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('index', compact('users'));
    }
    public function chat(Request $request , $id)
    {
        $senderId = auth()->id();
        $reciver = User::find($id); 
        $messages = Message::where(function($query) use ($senderId, $id) {
            $query->where('senderId', $senderId)
                  ->where('reciverId', $id);
        })->orWhere(function($query) use ($senderId, $id) {
            $query->where('senderId', $id)
                  ->where('reciverId', $senderId);
        })->get();
        return view('chat', compact('senderId', 'reciver' , 'messages'));
    } 
public function store(Request $request , $id)
{
    $request->validate([
        'message' => 'required|string',
    ]);
    $message = Message::create([
        'senderId' => auth()->id(),
        'reciverId' => $id,
        'message' => $request->message,
    ]);  
    
    Log::info('About to broadcast event to channel: test.' . $id);
    event(new testEvenet($message , $id));
    Log::info('Event broadcasted');
    
    return redirect()->back();
}
}
