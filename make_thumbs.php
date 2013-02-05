<?php
/**
 * Generate thumbnail files for album art based on higher resolution source files.
 *
 * @author    Justin Filip <jfilip@gmail.com>
 * @copyright 2010 Justin Filip - http://jfilip.ca/
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


define('DIR_IMAGES', dirname(__FILE__) . '/images/raw');
define('DIR_THUMBS', dirname(__FILE__) . '/images/thumbs');
define('THUMB_SIZE', 250);


if (($dh = opendir(DIR_IMAGES)) === false) {
    die('No images');
}


while ($fname = readdir($dh)) {
    $fpath = DIR_IMAGES . '/' . $fname;

    if (is_dir($fpath) || $fname == '.' || $fname == '..') {
        continue;
    }

    if (substr($fname, -4) == '.jpg') {
        $tpath = DIR_THUMBS . '/' . $fname;
        $tpath = str_replace('.jpg', '.png', $tpath);

        if (file_exists($path)) {
            unlink($tpath);
        }

        $img = imagecreatefromjpeg($fpath);

        $sizex = imagesx($img);
        $sizey = imagesy($img);
        $scalex = THUMB_SIZE;
        $scaley = THUMB_SIZE;

        if ($sizex != $sizey) {
            if ($sizex > $sizey) {
                $factor = THUMB_SIZE / $sizex;
                $scaley = floor($sizey * $factor);
            } else {
                $factor = THUMB_SIZE / $sizey;
                $scalex = floor($sizex * $factor);
            }
        }

        // Create the scaled copy of the image.
        $imgscale = imagecreatetruecolor($scalex, $scaley);
        imagecopyresampled($imgscale, $img, 0, 0, 0, 0, $scalex, $scaley, $sizex, $sizey);

        // Write out the scale image and free up memory.
        imagepng($imgscale, $tpath, 1);
        imagedestroy($img);
        imagedestroy($imgscale);
    }
}
