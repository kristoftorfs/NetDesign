<?php

$pi = $_SERVER['PATH_INFO'];
$w = $_GET['w'];
$h = $_GET['h'];
$bn = basename($pi);
$src = realpath(sprintf('%s/%s', __DIR__, $pi));
$cache = sprintf('%s/.imagecache/%s.%dx%d.jpg', __DIR__, $pi, $w, $h);
$cachedir = dirname($cache);
if (!is_dir($cachedir)) mkdir($cachedir, 0755, true);

// Create cache file
if (!file_exists($cache) || filemtime($cache) < filemtime($src)) {
    list($wo, $ho) = getimagesize($src);
    $im = imagecreatefromstring(file_get_contents($src));
    $ro = $wo / $ho;
    if ($w / $h > $ro) {
        $hn = $w / $ro;
        $wn = $w;
    } else {
        $wn = $h * $ro;
        $hn = $h;
    }
    $x = $wn / 2;
    $y = $hn / 2;
    $process = imagecreatetruecolor(round($wn), round($hn));
    imagecopyresampled($process, $im, 0, 0, 0, 0, $wn, $hn, $wo, $ho);
    $thumb = imagecreatetruecolor($w, $h);
    imagecopyresampled($thumb, $process, 0, 0, ($x - ($w / 2)), ($y - ($h / 2)), $w, $h, $w, $h);
    imagedestroy($process);
    imagedestroy($im);
    imagejpeg($thumb, $cache);
}

header('Content-Type: image/jpeg');
header('Content-Length: ' . filesize($cache));
readfile($cache);