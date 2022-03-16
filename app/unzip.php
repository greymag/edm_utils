<?php
function unzip_xml($path)
{
    $zip = new ZipArchive();
    if ($zip->open($path) === true) {
        $tempDest = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'EduUnzip' . uniqid();
        $zip->extractTo($tempDest);
        $zip->close();

        $files = scandir($tempDest);
        foreach ($files as $file) 
        {
            if ($file != '.' && $file != '..')
            {
                return $tempDest . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . $file . '.xml';
            }
        }
        return null;
    } else {
        return null;
    }
}
