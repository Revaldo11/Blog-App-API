<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function saveImage($image, $path = 'public')
    {
        //if image is not empty, save it
        if (!$image) {
            return null;
        }
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        //save image
        Storage::disk('local')->put($path . '/' . $imageName, file_get_contents($image));

        //return the path of the image
        //Url is the base url of the project
        return url('/storage/' . $path . '/' . $imageName);
    }
}
