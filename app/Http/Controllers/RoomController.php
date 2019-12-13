<?php

namespace App\Http\Controllers;

use App\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    //

    public function createRoom(Request $request)
    {

        $room = DB::table('chat_rooms')->where('users', $request->userIds)->first();
        if ($room != null) {
            return response()->json([
                'error' => ['code' => 302, 'message' => 'Room Already Exists'],
                'room' => $room
            ], Response::HTTP_OK);
        } else {


            $room = new ChatRoom();
            $room->users = $request->userIds;
            $room->save();
            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "Room Created"]
                , 'room' => $room
            ], Response::HTTP_OK);
        }


    }
}
