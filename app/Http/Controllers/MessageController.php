<?php

namespace App\Http\Controllers;

use App\ChatRoom;
use App\Messages;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    //
    public function createMessage(Request $request)
    {
//        'id','messageText','messageType','messageByName','messageById','roomId','time'

        $message = new Messages();
        $message->messageText = $request->messageText;
        $message->messageType = $request->messageType;
        $message->messageByName = $request->messageByName;
        $message->messageById = $request->messageById;
        $message->roomId = $request->roomId;
        $message->time = $request->time;
        $message->save();

        $chatRoom = ChatRoom::find($request->roomId);
        $users = $chatRoom->users;
        $abc = str_replace($request->messageById, '', $users);
        $abc = str_replace(',', '', $abc);
        $user = User::find($abc);

        $this->sendPushNotification($user->fcmKey,
            "New Message from " . $request->messageByName,
            $message->messageText, $request->roomId);
        $messages = DB::table('messages')->where('roomId', $request->roomId)->
        orderBy('id', 'desc')->take(100)->get();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => "false"]
            , 'messages' => $messages
        ], Response::HTTP_OK);
    }

    public function allRoomMessages(Request $request)
    {
        $messages = DB::table('messages')->where('roomId', $request->roomId)->
        orderBy('id', 'desc')->take(100)->get();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => "false"]
            , 'messages' => $messages
        ], Response::HTTP_OK);
    }

    public function userMessages(Request $request)
    {
        $results = DB::select('SELECT * from messages s where roomId In (Select id from chat_rooms where users like \'%' . $request->id . '%\' ) and id=(select max(id) from messages p where p.roomId=s.roomId) ORDER by s.time desc ');

        $userss = array();
        foreach ($results as $item) {
            $chatRoom = ChatRoom::find($item->roomId);
            $users = $chatRoom->users;

            $abc = str_replace($request->id, '', $users);
            $abc = str_replace(',', '', $abc);
//            return $abc;

            $us = User::find($abc);
//            echo $us->name;
            $item->userName = $us->name;


        }


        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => "false"]
            , 'messages' => $results
        ], Response::HTTP_OK);
    }

    public function sendPushNotification($fcm_token, $title, $message, $id)
    {
        $push_notification_key = 'AAAAunj0aLE:APA91bGsgLVRFUiWPSua5bQMGdIdVnyUGhQVVF2G1rHZwNd97vQRpOLOGjehUyAeKTKwV17qOYibjFtQEnFbirGobP5rAa-6CyjihximydygCprOA2t7dv6KwVf75W82tYTFepqa4xhz';
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array("authorization: key=" . $push_notification_key . "",
            "content-type: application/json"
        );

        $postdata = '{
            "to" : "' . $fcm_token . '",
                "notification" : {
                    "title":"' . $title . '",
                    "text" : "' . $message . '"
                },
            "data" : {
                "Message" : "' . $message . '",
                "Type":"chat",
                "Title":"' . $title . '",
                "Username":"Salman",
                "ChannelId" : ' . $id . ',
                "Id" : "' . $id . '",
                "is_read": 0
              }
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }


}
