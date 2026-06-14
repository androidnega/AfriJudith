<?php
/**
 * One-off utility: removes the near-white background of the logo
 * and writes a transparent PNG to public/assets/img/logo.png
 *
 * Run from project root:  php tools/make_transparent.php
 */

$src = $argv[1] ?? '';
$dst = $argv[2] ?? __DIR__ . '/../public/assets/img/logo.png';

if (!is_file($src)) {
    fwrite(STDERR, "Source image not found: {$src}\n");
    exit(1);
}

$info = getimagesize($src);
if ($info === false) {
    fwrite(STDERR, "Cannot read image: {$src}\n");
    exit(1);
}

switch ($info[2]) {
    case IMAGETYPE_PNG:  $im = imagecreatefrompng($src);  break;
    case IMAGETYPE_JPEG: $im = imagecreatefromjpeg($src); break;
    case IMAGETYPE_WEBP: $im = imagecreatefromwebp($src); break;
    case IMAGETYPE_GIF:  $im = imagecreatefromgif($src);  break;
    default:
        fwrite(STDERR, "Unsupported image type.\n");
        exit(1);
}

if (!$im) {
    fwrite(STDERR, "Failed to load image.\n");
    exit(1);
}

$w = imagesx($im);
$h = imagesy($im);

$out = imagecreatetruecolor($w, $h);
imagealphablending($out, false);
imagesavealpha($out, true);
$transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
imagefilledrectangle($out, 0, 0, $w, $h, $transparent);

// Threshold: any pixel close to white becomes transparent.
$threshold = 235;

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgb = imagecolorat($im, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        if ($r >= $threshold && $g >= $threshold && $b >= $threshold) {
            continue; // leave transparent
        }

        // Soft edge alpha for near-white pixels (anti-aliasing)
        $minChan = min($r, $g, $b);
        $alpha = 0;
        if ($minChan > 200) {
            // map 200..234 -> alpha 0..120 (semi-transparent edge)
            $alpha = (int) (($minChan - 200) / 34 * 120);
            $alpha = max(0, min(127, $alpha));
        }

        $color = imagecolorallocatealpha($out, $r, $g, $b, $alpha);
        imagesetpixel($out, $x, $y, $color);
    }
}

if (!is_dir(dirname($dst))) {
    mkdir(dirname($dst), 0775, true);
}

imagepng($out, $dst, 9);

echo "Transparent logo written to: {$dst}\n";
