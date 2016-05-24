<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->header('Authorization')) {
            return response()->json(['data' => $request->header('Authorization')], 401);
        } else {
            $user = explode('token:', $request->header('Authorization'));

            $token = str_random(60);

            $users = DB::table('users')->where('email', $user[0])->get();

            if (empty($users)) {
                return response()->json(['error' => 'email doesn\'t exist'], 401);
            }

            if ($users[0]->remember_token != $user[1]) {
                return response()->json(['data' => $users[0]->remember_token . ' ' . $user[1]], 401);
            }

            try {
                DB::table('users')
                    ->where('email', $user[0])
                    ->update([
                        'remember_token' => $token
                    ]);
            }catch(\Exception $e){
                return response()->json(['error' => $e], 401);
            }

            if ($request->path() != 'api/auth') {
                $request->email = $user[0];
                $request->token = $token;
                $request->data = 'user_logged';
                return $next($request);
            }
            
            return response()->json([
                'data' => 'user_logged',
                'token' => $token
            ], 200);

        }

    }
}
