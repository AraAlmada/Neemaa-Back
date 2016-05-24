<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class UserController extends Controller
{
    public function getUser(Request $request) {
        $users = DB::table('users')->where('email', $request->email)->get();

        return response()->json([
            'data' => $request->data,
            'token' => $request->token,
            'response' => $users
        ], 200);
    }

    public function getNeemStyler(Request $request) {
        $users = DB::table('neemstylers')->where('email', $request->email)->get();

        return response()->json([
            'data' => $request->data,
            'token' => $request->token,
            'response' => $users
        ], 200);
    }

    public function updateUser(Request $request) {
        $users = DB::table('users')->where('email', $request->email)->get();

        if (empty($users)) {
            return response()->json(['error' => 'user_not_found']);
        }

        try {
            DB::table('users')->where('email', $request->email)->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'adress' => $request->adress,
                'cp' => $request->cp,
                'sex' => $request->sex,
                'birthdate' => $request->birthdate,
                'carnation_type' => $request->carnation_type,
                'carnation_color' => $request->carnation_color,
                'hair_type' => $request->hair_type,
                'hair_color' => $request->hair_color
            ]);
        } catch(Exception $e){
            return response()->json(['error' => $e], 400);
        }

        return response()->json([
            'data' => $request->data,
            'token' => $request->token,
            'response' => $users
        ], 200);
    }

    public function updateUserToNeemstyler(Request$request) {
        $users = DB::table('users')->where('email', $request->email)->get();

        if (empty($users)) {
            return response()->json(['error' => 'user_not_found']);
        }

        DB::table('users')->delete();

        DB::table('neemstylers')->insert([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'password' => $users[0]->password,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'active' => 1
        ]);

        return response()->json([
            'data' => $request->data,
            'token' => $request->token,
            'response' => $users
        ], 200);
    }

    public function updateNeemStyler(Request $request) {
        $users = DB::table('neemstylers')->where('email', $request->email)->get();

        if (empty($users)) {
            return response()->json(['error' => 'user_not_found']);
        }

        try {
            DB::table('neemstylers')->where('email', $request->email)->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'adress' => $request->adress,
                'cp' => $request->cp,
                'sex' => $request->sex,
                'city' => $request->city,
                'birthdate' => $request->birthdate,
                'home' => $request->home1,
                'saloon' => $request->saloon1
            ]);
        } catch(Exception $e){
            return response()->json(['error' => $e], 400);
        }

        return response()->json([
            'data' => $request->data,
            'token' => $request->token,
            'response' => $request->all()
        ], 200);
    }
}
