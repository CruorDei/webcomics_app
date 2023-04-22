<?php 
namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService {
    private $params;
    
    public function __construct(ParameterBagInterface $params) {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, string $name, ?string $folder = '', ?int  $width = 300, ?int $height = 300) {
        
        $picture_info = getimagesize($picture);
        
        if($picture_info === false){
            throw new Exception("tailles d'image incorrect");
        }
        
        switch($picture_info['mime']){
            case 'image/png'; $picture_source = imagecreatefrompng($picture); break;
            case 'image/jpeg'; $picture_source = imagecreatefromjpeg($picture); break;
            case 'image/webp'; $picture_source = imagecreatefromwebp($picture); break;
            default: throw new Exception("format d'image incorrect");
        }
        
        $imageWidth = $picture_info[0];
        $imageHeight = $picture_info[1];
        
        switch ($imageWidth <=> $imageHeight){
            case -1: $squareSize = $imageWidth; $src_x = 0; $src_y = ($imageHeight - $squareSize) / 2; break;
            case 0: $squareSize = $imageWidth; $src_x = 0; $src_y = ($imageHeight - $squareSize) / 2; break;
            case 1: $squareSize = $imageHeight; $src_y = 0; $src_x = ($imageWidth - $squareSize) / 2; break;
        }
        $fichier =   $name . '-' . $imageWidth . 'x' . $imageHeight . '-' . md5(uniqid(rand(1, 10000)), false) . '.webp';
        // $resized_pic = imagecreatetruecolor($width, $height);
        // imagecopyresampled($resized_pic, $picture_source, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);
        
        $path = $this->params->get('image_directory') . $folder;
        
        // if(!file_exists($path . '/mini/')){ mkdir($path . '/mini/', 0755, true); }
        
        // imagewebp($resized_pic, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);
        $picture->move($path . '/', $fichier);
        
        return $fichier;
    }

    public function delete(string $fichier, ?string $folder, ?int  $width = 300, ?int $height = 300) {
        if($fichier !== 'default.webp'){
            $success = false;
            $path = $this->params->get('image_directory') . $folder;
            // $mini = $path. '/mini/' . $width . 'x' . '-' . $height . $fichier;

            // if(file_exists($mini)){ unlink($mini); $success = true; }
            
            $original = $path . '/' . $fichier;

            if(file_exists($original)){ unlink($original); $success = true; }
            
            return $success;
        }

        return false;
    }
}
