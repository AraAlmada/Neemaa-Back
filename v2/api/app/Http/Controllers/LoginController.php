<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mockery\CountValidator\Exception;
use Validator;
use App\Http\Requests;
use DB;
use Hash;
use Mail;

class LoginController extends Controller
{
    public function index(Request $request) {
        $token = str_random(60);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'error_form'], 401);
        }

        $users = DB::table('users')->where('email', $request->email)->get();
        $neemstyler = DB::table('neemstylers')->where('email', $request->email)->get();

        if (!empty($users)) {
            if (Hash::check($request->password, $users[0]->password)) {
                try {
                    DB::table('users')
                        ->where('email', $request->email)
                        ->update([
                            'remember_token' => $token
                        ]);
                    $validate = $users[0]->active;
                    $admin = $users[0]->admin;
                }catch(Exception $e){
                    return response()->json(['error' => $e], 401);
                }
            } else {
                return response()->json(['error' => 'password_error'], 401);
            }

            if ($validate == 1) {
                if ($admin) {
                    return response()->json([
                        'data' => 'admin_logged',
                        'token' => $token
                    ], 200);
                }
                return response()->json([
                    'data' => 'user_logged',
                    'token' => $token
                ], 200);
            } else {
                return response()->json([
                    'error' => 'user_not_valide',
                    'email' => $request->email
                ], 401);
            }
        }

        if (!empty($neemstyler)) {
            if (Hash::check($request->password, $neemstyler[0]->password)) {
                try {
                    DB::table('neemstylers')
                        ->where('email', $request->email)
                        ->update([
                            'remember_token' => $token
                        ]);
                    $validate = $neemstyler[0]->active;
                }catch(Exception $e){
                    return response()->json(['error' => $e], 401);
                }
            } else {
                return response()->json(['error' => 'password_error'], 401);
            }


            if ($validate) {
                return response()->json([
                    'data' => 'neemstyler_logged',
                    'token' => $token
                ], 200);
            } else {
                return response()->json([
                    'error' => 'user_not_valide',
                    'email' => $request->email
                ], 401);
            }
        }

        return response()->json(['error' => 'email_doesnt_exist'], 401);
    }

    public function recovery(Request $request) {
        $token = str_random(60);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'error_form'], 401);
        }

        $this->emailTo($request, 'email.password', ['token' => $token], 'Reinitialisation mot de passe');

        $email = DB::table('password_resets')->where('email', $request->email)->get();

        if (!empty($email)) {
            try {
                DB::table('password_resets')
                    ->where('email', $request->email)
                    ->update([
                        'token' => $token,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
            } catch(Exception $e){
                return response()->json(['error' => $e], 400);
            }
        } else {
            try {
                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } catch(Exception $e){
                return response()->json(['error' => $e], 400);
            }
        }

        return response()->json(['data' => 'email_send'], 200);
    }

    public function recoveryPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|max:20',
            'token' => 'required|min:60|max:60'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'error_form'], 401);
        }

        $email = DB::table('password_resets')->where('token', $request->token)->get();
        
        if (empty($email)) {
            return response()->json(['error' => 'invalid_token'], 400);
        } else {
            $user = DB::table('users')->where('email', $email[0]->email)->get();
            $neemStyler = DB::table('neemstylers')->where('email', $email[0]->email)->get();

            if (empty($user) && empty($neemStyler)) {
                return response()->json(['error' => 'user_error'], 400);
            }
            if (!empty($neemStyler)) {
                try {
                    DB::table('neemstylers')
                        ->where('email', $neemStyler[0]->email)
                        ->update([
                            'password' => bcrypt($request->password),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                } catch(Exception $e){
                    return response()->json(['error' => $e], 400);
                }
            }
            if (!empty($user)) {
                try {
                    DB::table('users')
                        ->where('email', $user[0]->email)
                        ->update([
                            'password' => bcrypt($request->password),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                } catch(Exception $e){
                    return response()->json(['error' => $e], 400);
                }
            }

            try {
                DB::table('password_resets')->where('token', $request->token)->delete();
            } catch (Exception $e) {
                return response()->json(['error' => $e], 400);
            }

            return response()->json(['data' => 'password_reset'], 200);
        }
    }

    public function emailTo($person, $view, $data, $subject)
    {
        Mail::send($view, $data, function($message) use($person, $subject)
        {
            $message->from('notificiation@neemaa.com', $subject);

            $message->to($person->email)
                ->subject($subject);
        });
    }

    public function resendMail(Request $request, $email) {
        $token = str_random(60);
        $user = DB::table('users')->where('email', $email)->get();
        $neemStyler = DB::table('neemstylers')->where('email', $email)->get();

        if (!empty($user)) {
            try {
                DB::table('users')
                    ->where('email', $email)
                    ->update([
                        'token_activator' => $token
                    ]);
            } catch(Exception $e){
                return response()->json(['error' => $e], 400);
            }
        }

        if (!empty($neemStyler)) {
            try {
                DB::table('neemstylers')
                    ->where('email', $email)
                    ->update([
                        'token_activator' => $token
                    ]);
            } catch(Exception $e){
                return response()->json(['error' => $e], 400);
            }
        }

        $request->email = $email;
        $this->emailTo($request, 'email.confirm', ['token' => $token], 'Confirmer le comte Neemaa');

        return response()->json(['data' => 'email_resend_to_' . $email], 200);

    }
}
