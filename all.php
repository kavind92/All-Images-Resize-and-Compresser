<?php
//1.Put ALL images in a folder Which are need to compress or resize
$foldername = "all_images";
//2.SET image width height quality
$all_img_width = "700";
$all_img_height = "500";
$all_img_quality = "60"; //value range : 1 to 100
//3.Then Run All.php file
//4.Wait Few Minutes. Time Taken based on number of images. check compressionLog.txt for log. Thats all.

include("resize-class.php");

function file1($text) {
    $open = fopen("compressionLog.txt", "a");
    fwrite($open, $text);
    fclose($open);
}

function fileDisplay() {
    echo readfile("compressionLog.txt");
}

function get_file_extension($file_name) {
    return substr(strrchr($file_name, '.'), 1);
}

function getDirContents($dir, &$results = array()) {
    
    global $all_img_width;
    global $all_img_height;
    global $all_img_quality;

    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            //$results[] = $path;
            $path;

            $Filename = basename("$path") . PHP_EOL;

            $supported_image = array('gif', 'jpg', 'jpeg', 'png');

            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            if (in_array($ext, $supported_image)) {

                $imagename = $path;

                list($imagenameWid, $height) = getimagesize($imagename);
                //$imagenameHei = imagesy($imagename);
                

                    ob_start();
                    file1($path);
                    fileDisplay();
                    ob_end_clean();

                    // *** 1) Initialize / load image
                    $resizeObj = new resize($imagename);

                    // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
                    $resizeObj -> resizeImage($all_img_width, $all_img_height, 'exact');
                    //$resizeObj -> resizeImage1(0, 800);
                    //$resizeObj->resizeImage1(1280, 0);

                    // *** 3) Save image and quality
                    $resizeObj->saveImage($imagename, $all_img_quality);

                    $msg = $imagename . " compressed successfully \n";
                    ob_start();
                    file1($msg);
                    fileDisplay();
                    ob_end_clean();
                
            }
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            //$results[] = $path;
        }
    }

    //return $results;
}

//var_dump(getDirContents('bin'));
getDirContents($foldername);
