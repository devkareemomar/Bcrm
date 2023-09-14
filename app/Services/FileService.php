<?php


namespace App\Services;

use Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{

    public function savePublicFile(UploadedFile $file, $path, $name = null)
    {
        $extension = empty(($e = $file->getClientOriginalExtension())) ? $file->extension() : $e;

        if ($name) {
            $fileName = $name . '.' .  $extension;
        } else {
            // generate token
            $token = md5(Str::random(40) . microtime());
            // appened token to file extension
            $fileName = $token . "." . $extension;
        }

        // save file and return path
        $path = $file->storeAs("public/" . $path, $fileName);

        return $path;
    }

    public function saveSeoFile(UploadedFile $file , $type)
    {
        // rename file
        $name = $type . "." . $file->getClientOriginalExtension();
        // dd($file);
        // save file and return path
        $path = $file->storeAs("public",$name);
        // dd($path);

        return $path;
    }// save files seo

    public function deleteSeoFile($name)
    {
        if ($name) {
            if (file_exists(base_path($name))){
                Storage::disk('local')->delete('public/'.$name);
            }
        }

        return;

    }// delete files seo


    public function renameFile($path, $oldName, $newName)
    {
        if ($path) {
            $newPath = str_replace(
                $oldName,
                $newName,
                $path,
            );
            Storage::disk('local')->move($path, $newPath);
            return $newPath;
        }
    }

    public function deleteFile($path)
    {
        if ($path) {
            Storage::disk('local')->delete($path);
        }

        return;
    }


    public function deleteFiles(array $paths)
    {
        foreach ($paths as $path) {
            if ($path) {
                Storage::disk('local')->delete($path);
            }
        }

        return;
    }
}
