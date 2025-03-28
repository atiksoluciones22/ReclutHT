<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        return view('panel.upload-document');
    }

    public function show($file)
    {
        $setting = \App\Models\Setting::firstOrFail();

        // Establecer las credenciales del disco FTP
        config([
            'filesystems.disks.ftp.host' => $setting->ftp_server,
            'filesystems.disks.ftp.username' => $setting->ftp_user_name,
            'filesystems.disks.ftp.password' => $setting->ftp_user_pass,
        ]);

        $file = 'www/' . $setting->ruta . '/' . $file;

        $fileContents = Storage::disk('ftp')->get($file);

        $mimeType = Storage::disk('ftp')->mimeType($file);

        return response($fileContents)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $file . '"');
    }
}
