<?php
require('ImgProcess.php');

// Directory to store Processed Image
$dir = 'test';

// Name you want to store file with
$file = 'filename_to_save_with.webp';

$setting = array(
   'directory' => $dir, 
   'file_type' => array(
     'image/jpeg',
     'image/png',
     'image/gif'
   )
);

$ImgCompressor = new ImgCompressor($setting);
$jw = new ImageToWebp();

  /* Arguments: 'cup.jpg' - Original image path, 'jpg' - Output format, 5 - Compression level (0-9)
   */
  
$result = $ImgCompressor->run('cup.jpg', 'jpg', 5);

if ($result['status'] === 'success') {
    $compressedImageName = $result['data']['compressed']['name'];
    
    /* 
    Arguments: Compressed image path, Output WebP image path, Quality level (95 in this case) 
    */
    $jw->convert($dir . '/' . $compressedImageName, $dir.'/'.$file,95);
       unlink($dir.'/'.$compressedImageName);
       echo "Done";
} else {
    echo 'Error: ' . $result['message'];
}

?>
