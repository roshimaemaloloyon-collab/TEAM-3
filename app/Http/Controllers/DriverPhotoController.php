<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverPhotoController extends Controller
{
    public function show($id)
    {
        $driver = Driver::find($id);
        $name = $driver ? $driver->full_name : 'Driver';
        return $this->renderInitialsAvatar($name);
    }

    public function adminAvatar(Request $request)
    {
        $name = 'User';
        if (auth()->check()) {
            $user = auth()->user();
            $name = $user->name ?? 'User';
        }
        return $this->renderInitialsAvatar($name);
    }

    private function renderInitialsAvatar($name)
    {
        // Extract initials (e.g. "Juan Dela Cruz" -> "JDC")
        $words = preg_split('/[\s_-]+/', trim($name));
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= strtoupper($w[0]);
            }
        }
        if (empty($initials)) {
            $initials = 'DR';
        }
        $initials = substr($initials, 0, 3); // Max 3 initials (e.g. JDC)

        $width = 240;
        $height = 240;
        $img = imagecreatetruecolor($width, $height);
        imagesavealpha($img, true);

        // Transparent canvas background
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);

        // Palette of stylish dark background colors based on name string
        $bgColors = [
            [12, 40, 72],   // Deep Navy
            [16, 185, 129], // Emerald Green
            [59, 130, 246], // Royal Blue
            [139, 92, 246], // Purple
            [236, 72, 153], // Pink/Rose
            [245, 158, 11], // Gold Amber
            [239, 68, 68],  // Crimson
            [14, 165, 233], // Sky Blue
        ];
        $colorIdx = abs(crc32($name)) % count($bgColors);
        $c = $bgColors[$colorIdx];

        $bgColor = imagecolorallocate($img, $c[0], $c[1], $c[2]);
        $white = imagecolorallocate($img, 255, 255, 255);
        $border = imagecolorallocate($img, 255, 255, 255);

        // Draw solid avatar circle
        imagefilledellipse($img, 120, 120, 228, 228, $bgColor);
        imagesetthickness($img, 4);
        imageellipse($img, 120, 120, 228, 228, $border);

        // Render Initials text using built-in font
        $fontSize = strlen($initials) > 2 ? 5 : 5; // Built-in font 5 is largest
        $fontWidth = imagefontwidth($fontSize);
        $fontHeight = imagefontheight($fontSize);

        // Scale text using manual block drawing for high readability
        $scale = strlen($initials) > 2 ? 4 : 5; // Scale factor
        $textWidth = strlen($initials) * $fontWidth * $scale;
        $textHeight = $fontHeight * $scale;

        $startX = (int)((240 - $textWidth) / 2);
        $startY = (int)((240 - $textHeight) / 2);

        // Render text onto a small temporary buffer then upscale
        $tempW = strlen($initials) * $fontWidth;
        $tempH = $fontHeight;
        $tempImg = imagecreatetruecolor($tempW, $tempH);
        $tempBg = imagecolorallocate($tempImg, 0, 0, 0);
        $tempFg = imagecolorallocate($tempImg, 255, 255, 255);
        imagefill($tempImg, 0, 0, $tempBg);
        imagestring($tempImg, $fontSize, 0, 0, $initials, $tempFg);

        // Copy scaled font pixels to avatar
        for ($x = 0; $x < $tempW; $x++) {
            for ($y = 0; $y < $tempH; $y++) {
                $rgb = imagecolorat($tempImg, $x, $y);
                if (($rgb & 0xFF) > 128) {
                    imagefilledrectangle(
                        $img,
                        $startX + ($x * $scale),
                        $startY + ($y * $scale),
                        $startX + (($x + 1) * $scale) - 1,
                        $startY + (($y + 1) * $scale) - 1,
                        $white
                    );
                }
            }
        }
        imagedestroy($tempImg);

        header('Content-Type: image/png');
        imagepng($img);
        imagedestroy($img);
        exit;
    }
}
