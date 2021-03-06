<?php
/**
 *
 * This file is part of the PHP-NUKE Software package.
 *
 * @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('NUKE_FILE')) {
    die("You can't access this file directly...");
}

Header("Content-type: image/png");
class textPNG
{
    var $font = 'utf8/tahoma.ttf'; //default font. directory relative to script directory.
    var $msg = "no text"; // default text to display.
    var $size = 24; // default font size.
    var $rot = 0; // rotation in degrees.
    var $pad = 0; // padding.
    var $transparent = 1; // transparency set to on.
    var $red = 0; // black text...
    var $grn = 0;
    var $blu = 0;
    var $bg_red = 255; // on white background.
    var $bg_grn = 255;
    var $bg_blu = 255;

    function draw()
    {
        $width = 0;
        $height = 0;
        $offset_x = 0;
        $offset_y = 0;
        $bounds = [];
        $image = "";

        // get the font height.
        $bounds = ImageTTFBBox($this->size, $this->rot, $this->font, "W");
        if ($this->rot < 0) {
            $font_height = abs($bounds[7] - $bounds[1]);
        } elseif ($this->rot > 0) {
            $font_height = abs($bounds[1] - $bounds[7]);
        } else {
            $font_height = abs($bounds[7] - $bounds[1]);
        }
        // determine bounding box.
        $bounds = ImageTTFBBox(
            $this->size,
            $this->rot,
            $this->font,
            $this->msg
        );
        if ($this->rot < 0) {
            $width = abs($bounds[4] - $bounds[0]);
            $height = abs($bounds[3] - $bounds[7]);
            $offset_y = $font_height;
            $offset_x = 0;
        } elseif ($this->rot > 0) {
            $width = abs($bounds[2] - $bounds[6]);
            $height = abs($bounds[1] - $bounds[5]);
            $offset_y = abs($bounds[7] - $bounds[5]) + $font_height;
            $offset_x = abs($bounds[0] - $bounds[6]);
        } else {
            $width = abs($bounds[4] - $bounds[6]);
            $height = abs($bounds[7] - $bounds[1]);
            $offset_y = $font_height;
            $offset_x = 0;
        }

        $image = imagecreate(
            $width + $this->pad * 2 + 1,
            $height + $this->pad * 2 + 1
        );
        $background = ImageColorAllocate(
            $image,
            $this->bg_red,
            $this->bg_grn,
            $this->bg_blu
        );
        $foreground = ImageColorAllocate(
            $image,
            $this->red,
            $this->grn,
            $this->blu
        );

        if ($this->transparent) {
            ImageColorTransparent($image, $background);
        }
        ImageInterlace($image, false);

        // render the image
        ImageTTFText(
            $image,
            $this->size,
            $this->rot,
            $offset_x + $this->pad,
            $offset_y + $this->pad,
            $foreground,
            $this->font,
            $this->msg
        );

        // output PNG object.
        imagePNG($image);
    }
}

$text = new textPNG();
$reqmsg = filter_var($_REQUEST['msg'], FILTER_SANITIZE_STRING); // Nuke 8.4 fix
if (isset($reqmsg)) {
    $text->msg = $reqmsg;
} // text to display // Nuke 8.4 fix
if (isset($_REQUEST['font'])) {
    $text->font = $_REQUEST['font'];
} // font to use (include directory if needed).
if (isset($_REQUEST['size'])) {
    $text->size = $_REQUEST['size'];
} // size in points
if (isset($_REQUEST['rot'])) {
    $text->rot = $_REQUEST['rot'];
} // rotation
if (isset($_REQUEST['pad'])) {
    $text->pad = $_REQUEST['pad'];
} // padding in pixels around text.
if (isset($_REQUEST['red'])) {
    $text->red = $_REQUEST['red'];
} // text color
if (isset($_REQUEST['grn'])) {
    $text->grn = $_REQUEST['grn'];
} // ..
if (isset($_REQUEST['blu'])) {
    $text->blu = $_REQUEST['blu'];
} // ..
if (isset($_REQUEST['bg_red'])) {
    $text->bg_red = $_REQUEST['bg_red'];
} // background color.
if (isset($_REQUEST['bg_grn'])) {
    $text->bg_grn = $_REQUEST['bg_grn'];
} // ..
if (isset($_REQUEST['bg_blu'])) {
    $text->bg_blu = $_REQUEST['bg_blu'];
} // ..
if (isset($_REQUEST['tr'])) {
    $text->transparent = $_REQUEST['tr'];
} // transparency flag (boolean).

$text->draw();

// GO!!!!!
?>
    