<?php
require 'vendor/autoload.php';

use Intervention\Image\ImageManager;

define('IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'].'/images/');

?>

<form method="post" enctype="multipart/form-data" action="">
    File: <input type="file" name="file"><input type="submit" value="Загрузить">
</form>

<?php

$src = 'source.jpg';
$res = 'result.jpg';

if (!empty($_FILES['file'])) {
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $src = 'source.' . $ext;
    $res = 'result.' . $ext;
    move_uploaded_file($_FILES['file']['tmp_name'], IMAGE_PATH . $src);
}

if (file_exists(IMAGE_PATH . $src)) {
    $manager = new ImageManager(array('driver' => 'imagick'));
    $image = $manager->make(IMAGE_PATH . $src);
    $image->resize(400, null, function ($image) {
        $image->aspectRatio();
    })
        ->text('Loftschool.com', $image->width() - 10, $image->height() - 10, function ($font) {
            $font->file('images/arial.ttf');
            $font->size(14);
            $font->color('#efefef');
            $font->align('right');
            $font->valign('bottom');
        })
        ->rotate(45)
        ->save(IMAGE_PATH . $res, 90);

    ?>
    <h4>Source:</h4>
    <img src="images/<?php echo $src; ?>"><br>
    <h4>Result:</h4>
    <img src="images/<?php echo $res; ?>">
    <?php
}
?>