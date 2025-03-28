<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function uploadFile($file_base64, $key = '', $folder = '')
    {
        $fileData = explode(';base64,', $file_base64);

        $extension = get_array_value(explode('/', get_array_value($fileData, 0)), 1);

        $file = base64_decode(get_array_value($fileData, 1));

        $fileName = $key . '_' . uniqid() . '.' . $extension;

        $setting = \App\Models\Setting::firstOrFail();

        // Establecer las credenciales del disco FTP
        config([
            'filesystems.disks.ftp.host' => $setting->ftp_server,
            'filesystems.disks.ftp.username' => $setting->ftp_user_name,
            'filesystems.disks.ftp.password' => $setting->ftp_user_pass,
        ]);

        Storage::disk('ftp')->put("www/$setting->ruta" . $folder . '/' . $fileName, $file);

        return $fileName;
    }
}
