<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileDownloadController extends Controller
{
    public function download($filename)
    {
        $path = storage_path('app/' . $filename . '.txt');

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            abort(404, 'File not found');
        }
    }
}
