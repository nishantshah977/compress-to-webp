<?php
class ImgCompressor {
    private $setting;

    public function __construct($setting) {
        $this->setting = $setting;
    }

    private function create($image, $name, $type, $c_type, $level) {
        $im_name = time() . $name;
        $im_output = $this->setting['directory'] . '/' . $im_name;

        $im = $this->createImage($image, $type);

        $im_type = $this->compressImage($im, $im_output, $c_type, $level);

        imagedestroy($im);

        $im_size = filesize($im_output);
        $data = [
            'original' => [
                'name' => $name,
                'type' => $type
            ],
            'compressed' => [
                'name' => $im_name,
                'type' => $im_type,
                'size' => $im_size
            ]
        ];
        return $data;
    }

    private function createImage($image, $type) {
        if ($type === 'image/jpeg') {
            return imagecreatefromjpeg($image);
        } elseif ($type === 'image/gif') {
            return imagecreatefromgif($image);
        } else {
            return imagecreatefrompng($image);
        }
    }

    private function compressImage($im, $im_output, $c_type, $level) {
        if ($c_type === 'jpeg' || $c_type === 'jpg') {
            $im_type = 'image/jpeg';
            imagejpeg($im, $im_output, 100 - ($level * 10));
        } elseif ($c_type === 'gif') {
            $im_type = 'image/gif';
            if ($this->checkTransparent($im)) {
                imagegif($im, $im_output);
            } else {
                imagegif($im, $im_output);
            }
        } elseif ($c_type === 'png') {
            $im_type = 'image/png';
            if ($this->checkTransparent($im)) {
                imagepng($im, $im_output, $level);
            } else {
                imagepng($im, $im_output, $level);
            }
        }
        return $im_type;
    }

    private function checkTransparent($im) {
        $width = imagesx($im);
        $height = imagesy($im);

        for ($i = 0; $i < $width; $i++) {
            for ($j = 0; $j < $height; $j++) {
                $rgba = imagecolorat($im, $i, $j);
                if (($rgba & 0x7F000000) >> 24) {
                    return true;
                }
            }
        }
        return false;
    }

    public function run($image, $c_type, $level = 0) {
    $im_info = getimagesize($image);
    $im_name = basename($image);
    $im_type = $im_info['mime'];

    $result = [];

    if (in_array($c_type, ['jpeg', 'jpg', 'JPG', 'JPEG', 'gif', 'GIF', 'png', 'PNG'])) {
        if (in_array($im_type, $this->setting['file_type'])) {
            if ($level >= 0 && $level <= 9) {
                $result['status'] = 'success';
                $result['data'] = $this->create($image, $im_name, $im_type, $c_type, $level);
            } else {
                $result['status'] = 'error';
                $result['message'] = 'Compression level: from 0 (no compression) to 9';
            }
        } else {
            $result['status'] = 'error';
            $result['message'] = 'Failed file type';
        }
    } else {
        $result['status'] = 'error';
        $result['message'] = 'Failed file type';
    }
    return $result;
}

}

// To Convert into Webp


  class ImageToWebp {

    private $source = null;

    protected function getImageResource () {

      // Find the extension of source image.
      $extension = strtolower( strrchr ( $this->source, '.' ) );

      // Convert image to resource object according to its type.
      switch ( $extension ) {
        case '.jpg':
        case '.jpeg':
          $img = @imagecreatefromjpeg( $this->source );
          break;
        case '.gif':
          $img = @imagecreatefromgif( $this->source );
          break;
        case '.png':
          $img = @imagecreatefrompng( $this->source );
          break;
        default:
          $img = false;
          break;
      }
      return $img;
    }

    public function convert ( $source, $destination, $quality = 80 ) {

      // Set default values globally
      $this->source = $source;

      // Convert to webp, yey
      imagewebp( $this->getImageResource(), $destination, $quality );

    }
  }
