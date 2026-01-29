<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentHasProgram
{
    /**
     * Handle an incoming request.
     * Ensures students have a program assigned before accessing keuzedelen.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isStudent() && !$user->program_id) {
            return redirect()->route('profile.edit')
                ->with('error', 'Je moet eerst een opleiding selecteren voordat je keuzedelen kunt bekijken.');
        }

        return $next($request);
    }
}
