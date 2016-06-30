<?php

namespace App\Models\Utils;

use Storage;
use File;
use App\Models\Image;
use Illuminate\Http\Response as IlluminateResponse;
use \Carbon\Carbon;

trait UploadableTrait
{
    public function uploadFile($file)
    {
        $name = $this->generateName();
        $path = $this->upload_dir . '/' . Carbon::now()->format('Y/m/d') . '/';

        $fullpath = $path.$name.$file->getExtension();

        try {
            Storage::put($fullpath, File::get($file));

            $file = $this->create([
                'path' => $path,
                'name' => $name,
                'size' => $file->getClientSize(),
                'ext' => $file->getExtension(),
                'type' => $file->getClientMimeType(),
            ]);

            return response()->json([
                'id' => $file->id
            ]);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteFile(Image $file)
    {
        // Now we deleting original file
        try {
            Storage::delete($file->path . $file->name . $file->ext);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }

        // Deleting rendered file
        try {
            Storage::delete($file->path . $file->name . '_rendered' . $file->ext);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }

        return response()->json([
            'status' => 'file_deleting_successfully',
            'file' => $file->id
        ]);
    }

    public function generateName($length = 12)
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $name = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $name[] = $alphabet[$n];
        }
        return implode($name);
    }
}