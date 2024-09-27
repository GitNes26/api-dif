<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    // use AuthorizesRequests, ValidatesRequests;

    /**
     * Funcion para guardar una imagen en directorio fisico, elimina y guarda la nueva al editar la imagen para no guardar muchas
     * imagenes y genera el path que se guardara en la BD
     * 
     * @param $image File es el archivo de la imagen
     * @param $destination String ruta donde se guardara fisicamente el archivo
     * @param $dir String ruta que mandara a la BD
     * @param $imgName String Nombre de como se guardará el archivo fisica y en la BD
     */
    public function ImgUpload($image, $destination, $dir, $imgName)
    {
        try {
            // return "ImgUpload->aqui todo bien";
            $type = "JPG";
            $permissions = 0777;

            if (file_exists("$dir/$imgName.PNG")) {
                // Establecer permisos
                if (chmod("$dir/$imgName.PNG", $permissions)) {
                    @unlink("$dir/$imgName.PNG");
                }
                $type = "JPG";
            } elseif (file_exists("$dir/$imgName.JPG")) {
                // Establecer permisos
                if (chmod("$dir/$imgName.JPG", $permissions)) {
                    @unlink("$dir/$imgName.JPG");
                }
                $type = "PNG";
            }
            $imgName = "$imgName.$type";
            $image->move($destination, $imgName);
            return "$dir/$imgName";
        } catch (\Error $err) {
            $msg = "error en imgUpload(): " . $err->getMessage();
            error_log($msg);
            return "$msg";
        }
    }
}