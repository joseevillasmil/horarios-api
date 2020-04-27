<?php

namespace App\Http\Middleware;

use App\Conection;
use App\Connection;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class DatabaseConnection
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

        $con = Connection::where('client', $request->client)->first();

        if ($con) {
            \DB::purge('pgcons');

            Config::set('database.connections.pgsql', [
                'driver' => 'pgsql',
                'host' => $con->host,
                'port' => $con->port,
                'database' => $con->database,
                'username' => $con->username,
                'password' => $con->pass,
                'charset' => 'utf8',
                'prefix' => '',
                'schema' => 'public',
                'sslmode' => 'prefer',
            ]);
            \DB::purge('pgsql');
        } else {
            return response()->json('invalid client',404);
        }

        $request->route()->forgetParameter('client');

        return $next($request);
    }
}
