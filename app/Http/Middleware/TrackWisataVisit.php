<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Wisata;
use App\Models\WisataVisit;

class TrackWisataVisit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Track visit ketika user mengakses halaman detail wisata
        if ($request->routeIs('wisata.show')) {
            $wisataId = $request->route('wisata') ?? $request->route('id');
            
            if ($wisataId) {
                $wisata = Wisata::find($wisataId);
                
                if ($wisata) {
                    // Catat dalam history
                    WisataVisit::create([
                        'wisata_id' => $wisata->id,
                        'user_id' => auth()->id(),
                        'ip_address' => $request->ip(),
                        'visited_at' => now(),
                    ]);

                    // Update counter visits
                    $wisata->incrementVisit();
                }
            }
        }

        return $response;
    }
}
