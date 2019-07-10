<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Musonza\Chat\Models\Message;
class MessageController extends Controller
{
    //
    public function getFiles(Request $request, Message $message)
    {
        $files = $message->attachments();
        if($files->count() >0){
            Log::debug('An informational message.');

        }
        return response( $files->get() );
    }
}
