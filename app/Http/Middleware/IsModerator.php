<?php

namespace App\Http\Middleware;

use Closure;

class IsModerator
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
        $user = Auth::user();
        //If its not user is not admin
        if($user->role != 1 || $user->role != 3 ){
           return response()->json(["error"=> true, "message" => "You are not a moderator"], 401);
        }
        return $next($request);
    }
}
