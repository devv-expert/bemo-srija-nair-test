<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function dbDump()
    {
        $fileName = strtotime("now") . '.sql';
        // Dumping DB 
        try {
            \Spatie\DbDumper\Databases\MySql::create()
                ->setDumpBinaryPath(config('app.dump_path'))
                ->setHost(config('database.connections.mysql.host'))
                ->setPort(config('database.connections.mysql.port'))
                ->setDbName(config('database.connections.mysql.database'))
                ->setUserName(config('database.connections.mysql.username'))
                ->setPassword(config('database.connections.mysql.password'))
                ->dumpToFile('storage/' . $fileName);
            return Storage::download('public/' . $fileName);
        } catch (\Exception $e) {
            Log::info($e);
            return array('message' => 'something went wrong');
        }
    }
}