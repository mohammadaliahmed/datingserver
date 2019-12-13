<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\http\UploadedFile;


class UserController extends Controller
{
    //


    public function register(Request $request)
    {
        $user = DB::table('users')->where('email', $request->email)->first();
        if ($user != null) {
            return response()->json([
                'error' => ['code' => 302, 'message' => 'email already exist'],
            ], Response::HTTP_OK);
        } else {
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->birthday = $request->birthday;
            $user->password = md5($request->password);
            $user->fcmKey = $request->fcmKey;
            $user->address = $request->address;
            $user->emailCode = $request->emailCode;
            $user->save();
//            $this->sendMail($request->email);
            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "false"]
                , 'user' => $user
            ], Response::HTTP_OK);


        }

    }

    public function login(Request $request)
    {
        $user = DB::table('users')->where('email', $request->email)->first();
        if ($user != null) {
            $user1 = DB::table('users')->where('email', $request->email)
                ->where('password', md5($request->password))->first();
            if ($user1 != null) {
                $userr = User::find($user->id);
                $userr->fcmKey = $request->fcmKey;

                $userr->update();
                return response()->json([
                    'error' => ['code' => 302, 'message' => 'false'],
                    'user' => $userr], Response::HTTP_OK);
            } else {
                return response()->json([
                    'error' => ['code' => Response::HTTP_OK, 'message' => "Wrong password"]

                ], Response::HTTP_OK);
            }

        } else {

            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "Wrong email"]

            ], Response::HTTP_OK);


        }

    }

    public function updateFcmKey(Request $request)
    {

        $userr = User::find($request->id);
        $userr->fcmKey = $request->fcmKey;

        $userr->update();
        return response()->json([
            'error' => ['code' => 302, 'message' => 'false'],
            'user' => $userr], Response::HTTP_OK);


    }

    public function allUsers(Request $request)
    {
        $users = DB::table('users')->where('city', $request->city)->get();

        return response()->json([
            'error' => ['code' => Response::HTTP_OK, 'message' => "false"],
            'user' => $users

        ], Response::HTTP_OK);

    }


    public function uploadProfilePicture(Request $request)
    {

        if ($request->hasFile('photo')) {
            //
            return "aasdfsdfsda";

        } else {
            return "aaa";


    }
//        if ($request->hasFile('profile')){
//            $image = $request->file('profile');
//            $name = time().'.'.$image->getClientOriginalExtension();
//            $destinationPath = public_path('/images');
//            $image->move($destinationPath, $name);
//            $this->save();
//
//            return back()->with('success','Image Upload successfully');
//        }else{
//            return "ni chala";
//        }
    }

    public function updateProfilePic(Request $request)
    {
        $users = DB::table('users')->where('id', $request->id)->get();
        if ($users != null) {
            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "false"],
                'user' => $users

            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "No user found"],

            ], Response::HTTP_OK);
        }


    }


    public function verifyUserViaEmail($emailCode)
    {

        $user = DB::table('users')->where('emailCode', $emailCode)->first();
        if ($user != null) {
            $userr = User::find($user->id);
            $userr->emailVerified = 1;
            $userr->update();
            return "Verified. Please login to the app";


        } else {
            return "Link Expired";

        }

    }

    public function phoneVerify(Request $request)
    {

        $user = DB::table('users')->where('id', $request->userId)->first();
        if ($user != null) {
            $userr = User::find($user->id);
            $userr->phoneVerified = 1;
            $userr->phoneNumber = $request->phoneNumber;
            $userr->update();
            return response()->json([
                'error' => ['code' => 302, 'message' => 'false'],
                'user' => $userr], Response::HTTP_OK);


        } else {
            return response()->json([
                'error' => ['code' => Response::HTTP_OK, 'message' => "No User found"]

            ], Response::HTTP_OK);

        }

    }

    public function sendMail($email)
    {

        $data = [
            'data' => "http://chatapp.com/sdfdsfsdfsdfsdfsdfsdfsfsdfsdfsdfsdfsdfsdfs",

        ];
//        $email="m.aliahmed0@gmail.com";
        Mail::send('mail', ["data1" => $data], function ($message) use ($email) {
            $message->to($email)->subject("New User Registration");
            $message->from('chat@gmail.com', 'Chat App');
        });

    }
}
