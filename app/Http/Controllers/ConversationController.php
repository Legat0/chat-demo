<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Message;
use App\Helper\AppHelper;
use Illuminate\Http\Request;
use Chat;
use Musonza\Chat\Models\Conversation;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $conversations = Chat::conversations()->conversation->all();
        return response($conversations);
    }

    public function store(Request $request)
    {
        $participants = [auth()->user()->id];
        $name = $request->input('name');
        $id = $request->input('id');
        if (is_integer($id)){
            //rename
            $conversation = Chat::renameConversation($id, $name);
        } else {
            $conversation = Chat::createConversation($participants, $name);
        }
        

        return response($conversation);
    }

    public function deleteConversation(Request $request,  Conversation $conversation)
    {
        //$Id = $request->input('Id');
       // Chat::conversations()->getById($Id)->delete();
        $conversation->delete();
        //Chat::deleteConversation($conversationId);
        return response('');
    }

    public function participants($conversationId)
    {
        $participants = Chat::conversations()->getById($conversationId)->users;

        return response($participants);
    }

    public function join(Request $request, Conversation $conversation)
    {
        Chat::conversation($conversation)->addParticipants(auth()->user());
        return response('');
    }

    public function leaveConversation(Request $request, Conversation $conversation)
    {
        Chat::conversation($conversation)->removeParticipants(auth()->user());
        return response('');
    }

    public function getMessages(Request $request, Conversation $conversation)
    {
        $messages = Chat::conversation($conversation)->for(auth()->user())->getMessages();

        return response($messages);
    }

    public function deleteMessages(Conversation $conversation)
    {
        Chat::conversation($conversation)->for(auth()->user())->clear();
        return response('');
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {

        if ( $request->has('file')){           
            $request->validate([
                'file' => 'required|file|max:1024',
            ]);
            
        }

        //$fileName =  AppHelper::instance()->GUID().'.'.request()->fileToUpload->getClientOriginalExtension();

        //$request->fileToUpload->storeAs('attachments',$fileName);

        $message = Chat::message($request->message)
                  ->from(auth()->user())
                  ->to($conversation)
                  ->send();

        if ( $request->has('file')){  
            
            $message->appendFile($request->file);
        }
        
        return response($message);
    }

    public function fileUploadPost()

    {

        $request->validate([
            'fileToUpload' => 'required|file|max:1024',
        ]);

        $fileName = "fileName".time().'.'.request()->fileToUpload->getClientOriginalExtension();

        $request->fileToUpload->storeAs('attachments',$fileName);

        request()->validate([

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

  

        $imageName = time().'.'.request()->image->getClientOriginalExtension();

  

        request()->image->move(public_path('images'), $imageName);

  

        return back()

            ->with('success','You have successfully upload image.')

            ->with('image',$imageName);

    }
}