<?php

namespace App\Http\Middleware;
use App\Http\Controllers\CustomAuthController;
use Closure;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CleanExpiredOtps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Calculate the time 5 minutes ago
         $expiryTime = Carbon::now()->subMinutes(5);
        
         // Delete OTPs older than 5 minutes
         Otp::where('created_at', '<=', $expiryTime)->delete(); 
 
         return $next($request);
    }
}
