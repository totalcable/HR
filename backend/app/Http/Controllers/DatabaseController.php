<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ifsnop\Mysqldump as IMysqldump;

class DatabaseController extends Controller
{
    public function backup(){

    }

    public function wholeDbBackup()
    {
        $dbhost = env('DB_HOST');
        $dbname = env('DB_DATABASE');
        $dbuser = env('DB_USERNAME');
        $dbpass = env('DB_PASSWORD');
        $msg='';
        $flag='';
        try {
            $dump = new IMysqldump\Mysqldump('mysql:host='.$dbhost.';dbname='.$dbname.'', ''.$dbuser.'', ''.$dbpass.'');



            $fileName='HRMTIS_dump'.date("Y-m-d_H-i-s");

            $file=public_path('DBbackup/'.$fileName.'.sql');
            if(!is_file($file)){
                fopen($file, "w");
            }
            $dump->start($file);

            $msg='Database backed up successfully';
            $flag='1';


        }

        catch (\Exception $e) {
//            return 'mysqldump-php error: ' . $e->getMessage();

            $msg='Problem with Database back up';
            $flag='0';

        }

        $fileInfo = array(
            'fileName' => $fileName,
            'msg' => $msg,
            'flag'=>$flag
        );

        return response()->json($fileInfo);
    }
}
