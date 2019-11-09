<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileControllerController extends Controller
{
    public function deleteFile(Request $r){



        $file_path = public_path().'/'.$r->filePath;


        unlink($file_path.$r->fileName);


    }
}
