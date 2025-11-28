<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\FirestoreHelper;

class FirebaseMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        $maintenance_settings = FirestoreHelper::getDocument('settings/maintenance_settings');
        if (!empty($maintenance_settings)) {
            if ($maintenance_settings['isMaintenanceModeForCustomer'] === true) {
                return response()->view('maintenance');
            }
        }
        return $next($request);
    }
}
