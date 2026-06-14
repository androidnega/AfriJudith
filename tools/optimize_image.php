<?php
/**
 * tools/optimize_image.php
 *
 * Reusable CLI tool for prepping images before they're committed to
 * public/assets/img/. It does three things every image needs:
 *
 *   1. Renames the file using an SEO-friendly slug (kebab-case,
 *      ASCII only) so Google can index it cleanly.
 *   2. Resizes so the longer edge is at most --max (default 512px).
 *      Logos rarely need more; photos can go up to 1600px.
 *   3. Re-encodes as PNG (preserves transparency) or JPEG with
 *      sensible compression so the file is web-light.
 *
 * Usage:
 *   php tools/optimize_image.php <source> <seo-name> [options]
 *
 * Options:
 *   --max=512                 Longer edge in pixels (default 512).
 *   --format=png|jpg          Force output format (default: keep source).
 *   --bg=transparent          Smooth white-to-alpha (forces PNG output).
 *
 * Examples:
 *   # Logo: take a JPEG with white background, strip the white
 *   php tools/optimize_image.php /tmp/raw.jpg judith-afriyie-logo \
 *        --max=512 --bg=transparent
 *
 *   # Project screenshot photo
 *   php tools/optimize_image.php /tmp/raw.jpg sales-dashboard-screenshot \
 *        --max=1600 --format=jpg
 *
 * Naming convention (going forward):
 *   subject-context-purpose[-variant].ext
 *     judith-afriyie-logo.png
 *     judith-afriyie-portrait.jpg
 *     sales-dashboard-screenshot.jpg
 *     react-portfolio-thumbnail.jpg
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "Run this from the command line.\n");
    exit(1);
}

if ($argc < 3) {
    fwrite(STDERR, "Usage: php tools/optimize_image.php <source> <seo-name> [--max=512] [--format=png|jpg]\n");
    exit(2);
}

$src       = $argv[1];
$seoName   = $argv[2];
$maxEdge   = 512;
$forceFmt  = null;
$stripWhite = false;

for ($i = 3; $i < $argc; $i++) {
    if (preg_match('/^--max=(\d+)$/', $argv[$i], $m)) {
        $maxEdge = max(16, (int) $m[1]);
    } elseif (preg_match('/^--format=(png|jpg|jpeg)$/i', $argv[$i], $m)) {
        $forceFmt = strtolower($m[1]) === 'jpeg' ? 'jpg' : strtolower($m[1]);
    } elseif ($argv[$i] === '--bg=transparent') {
        $stripWhite = true;
        $forceFmt   = 'png'; // alpha needs PNG
    }
}

if (!is_file($src)) {
    fwrite(STDERR, "Source not found: {$src}\n");
    exit(3);
}

$slug = slugify($seoName);
if ($slug === '') {
    fwrite(STDERR, "Invalid SEO name. Use words like 'judith-afriyie-logo'.\n");
    exit(4);
}

$info = getimagesize($src);
if (!$info) {
    fwrite(STDERR, "Source is not a recognised image.\n");
    exit(5);
}

[$srcW, $srcH, $type] = $info;
$srcImg = imageFromFile($src, $type);
if (!$srcImg) {
    fwrite(STDERR, "Unsupported image type: {$info['mime']}\n");
    exit(6);
}

[$dstW, $dstH] = fitWithin($srcW, $srcH, $maxEdge);
$dstImg = imagecreatetruecolor($dstW, $dstH);

imagealphablending($dstImg, false);
imagesavealpha($dstImg, true);
$transparent = imagecolorallocatealpha($dstImg, 0, 0, 0, 127);
imagefilledrectangle($dstImg, 0, 0, $dstW, $dstH, $transparent);

imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

if ($stripWhite) {
    whiteToAlpha($dstImg, $dstW, $dstH);
}

$ext = $forceFmt ?? ($type === IMAGETYPE_JPEG ? 'jpg' : 'png');
$outDir = realpath(__DIR__ . '/..') . '/public/assets/img';
if (!is_dir($outDir)) {
    mkdir($outDir, 0755, true);
}
$dst = $outDir . '/' . $slug . '.' . $ext;

$ok = $ext === 'jpg'
    ? imagejpeg($dstImg, $dst, 85)
    : imagepng($dstImg, $dst, 9);

if (!$ok) {
    fwrite(STDERR, "Failed to write {$dst}\n");
    exit(7);
}

printf(
    "OK  %s  (%dx%d, %s KB)\n",
    str_replace(realpath(__DIR__ . '/..') . '/', '', $dst),
    $dstW,
    $dstH,
    number_format(filesize($dst) / 1024, 1)
);

// ----------------- helpers -----------------

function slugify(string $name): string
{
    $s = strtolower($name);
    $s = preg_replace('~[^a-z0-9]+~', '-', $s);
    return trim($s ?? '', '-');
}

function fitWithin(int $w, int $h, int $max): array
{
    if ($w <= $max && $h <= $max) {
        return [$w, $h];
    }
    $ratio = $w >= $h ? $max / $w : $max / $h;
    return [max(1, (int) round($w * $ratio)), max(1, (int) round($h * $ratio))];
}

function imageFromFile(string $path, int $type)
{
    switch ($type) {
        case IMAGETYPE_PNG:  return imagecreatefrompng($path);
        case IMAGETYPE_JPEG: return imagecreatefromjpeg($path);
        case IMAGETYPE_WEBP: return function_exists('imagecreatefromwebp') ? imagecreatefromwebp($path) : false;
        case IMAGETYPE_GIF:  return imagecreatefromgif($path);
    }
    return false;
}

/**
 * Smoothly turn near-white, near-grayscale pixels into alpha-transparent
 * ones. Coloured pixels (the orange ring, the portrait) are untouched.
 *
 *   - threshold      Any pixel whose darkest channel >= this becomes
 *                    at least partially transparent.
 *   - chromaTol      How "white" a pixel must be: max(R,G,B) - min(R,G,B)
 *                    must be <= this to be treated as background.
 *
 * Alpha is ramped from 0 (opaque) at threshold to 127 (fully transparent)
 * at 255 — this preserves the anti-aliased edges of the artwork.
 */
function whiteToAlpha($img, int $w, int $h, int $threshold = 230, int $chromaTol = 12): void
{
    imagealphablending($img, false);
    imagesavealpha($img, true);

    $range = 255 - $threshold;
    if ($range <= 0) return;

    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $minRGB = min($r, $g, $b);
            $maxRGB = max($r, $g, $b);

            if ($minRGB < $threshold) continue;
            if (($maxRGB - $minRGB) > $chromaTol) continue;

            $alpha = (int) round(127 * (($minRGB - $threshold) / $range));
            if ($alpha <= 0) continue;

            imagesetpixel(
                $img, $x, $y,
                imagecolorallocatealpha($img, $r, $g, $b, min(127, $alpha))
            );
        }
    }
}
