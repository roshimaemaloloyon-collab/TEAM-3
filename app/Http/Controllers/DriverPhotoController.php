<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverPhotoController extends Controller
{
    public function show($id)
    {
        $driver = Driver::find($id);
        $gender = $driver ? strtolower($driver->gender ?? 'male') : 'male';
        return $this->renderAvatar($gender);
    }

    public function adminAvatar(Request $request)
    {
        $gender = 'male';
        if (auth()->check()) {
            $user = auth()->user();
            if (isset($user->gender)) {
                $gender = strtolower($user->gender);
            } elseif ($user->isDriver() || method_exists($user, 'driver')) {
                $driver = Driver::where('email', $user->email)->first();
                if ($driver) {
                    $gender = strtolower($driver->gender ?? 'male');
                }
            }
        }
        
        if ($request->has('gender')) {
            $gender = strtolower($request->get('gender'));
        }

        return $this->renderAvatar($gender);
    }

    private function renderAvatar($gender)
    {
        $isFemale = in_array($gender, ['female', 'f']);

        $width = 240;
        $height = 240;
        $img = imagecreatetruecolor($width, $height);
        imagesavealpha($img, true);

        // Colors
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);

        // Circular background (soft light blue-grey)
        $bg = imagecolorallocate($img, 240, 244, 248);
        // Outer dark navy border ring
        $borderDark = imagecolorallocate($img, 12, 40, 72);

        // Fill avatar circle
        imagefilledellipse($img, 120, 120, 224, 224, $bg);

        // Draw thick outer ring border
        imagesetthickness($img, 7);
        imageellipse($img, 120, 120, 224, 224, $borderDark);
        imagesetthickness($img, 1);

        // Skin tones
        $skin = imagecolorallocate($img, 252, 206, 162);
        $skinShadow = imagecolorallocate($img, 238, 184, 136);
        $neckShadow = imagecolorallocate($img, 230, 170, 120);

        // Hair / Hoodie dark navy color
        $darkNavy = imagecolorallocate($img, 20, 34, 56);
        $hoodieInner = imagecolorallocate($img, 28, 48, 76);
        $femaleTop = imagecolorallocate($img, 246, 243, 238);
        $strapColor = imagecolorallocate($img, 168, 118, 78);

        $cx = 120;

        if (!$isFemale) {
            // --- MALE AVATAR ---
            // Shoulders / Hoodie
            imagefilledellipse($img, $cx, 220, 175, 110, $darkNavy);
            // V-Neck hoodie cutout
            $vPoints = [
                $cx - 32, 165,
                $cx + 32, 165,
                $cx, 210
            ];
            imagefilledpolygon($img, $vPoints, 3, $hoodieInner);

            // Neck & Neck shadow
            imagefilledrectangle($img, $cx - 18, 130, $cx + 18, 175, $neckShadow);
            imagefilledrectangle($img, $cx - 16, 128, $cx + 16, 168, $skin);
            
            // Chest skin showing in V-neck
            $chestPoints = [
                $cx - 24, 165,
                $cx + 24, 165,
                $cx, 200
            ];
            imagefilledpolygon($img, $chestPoints, 3, $skin);

            // Face (Sleek smooth oval profile)
            imagefilledellipse($img, $cx, 112, 74, 94, $skin);

            // Ears
            imagefilledellipse($img, $cx - 37, 114, 14, 20, $skinShadow);
            imagefilledellipse($img, $cx + 37, 114, 14, 20, $skinShadow);
            imagefilledellipse($img, $cx - 36, 114, 10, 16, $skin);
            imagefilledellipse($img, $cx + 36, 114, 10, 16, $skin);

            // Male Modern Hairstyle (Layered navy hair)
            imagefilledellipse($img, $cx, 82, 80, 56, $darkNavy);
            
            // Sideburns & front fringe bangs
            $bangs1 = [$cx - 40, 95, $cx - 24, 65, $cx - 8, 92];
            imagefilledpolygon($img, $bangs1, 3, $darkNavy);
            $bangs2 = [$cx - 15, 92, $cx + 10, 62, $cx + 25, 95];
            imagefilledpolygon($img, $bangs2, 3, $darkNavy);
            $bangs3 = [$cx + 10, 95, $cx + 32, 70, $cx + 42, 98];
            imagefilledpolygon($img, $bangs3, 3, $darkNavy);

            // Additional Hair Top Volume
            imagefilledellipse($img, $cx - 12, 68, 64, 40, $darkNavy);
            imagefilledellipse($img, $cx + 15, 66, 50, 36, $darkNavy);

            // Hoodie Drawstrings
            imagesetthickness($img, 3);
            imageline($img, $cx - 16, 166, $cx - 16, 205, $darkNavy);
            imageline($img, $cx + 16, 166, $cx + 16, 205, $darkNavy);

        } else {
            // --- FEMALE AVATAR ---
            // Back Hair (Long flowing dark hair behind shoulders)
            imagefilledellipse($img, $cx, 135, 126, 140, $darkNavy);

            // Shoulders / Top
            imagefilledellipse($img, $cx, 222, 165, 105, $femaleTop);

            // Bag Strap on Left Shoulder
            imagefilledrectangle($img, $cx + 32, 160, $cx + 46, 230, $strapColor);

            // Neck
            imagefilledrectangle($img, $cx - 16, 130, $cx + 16, 175, $neckShadow);
            imagefilledrectangle($img, $cx - 15, 126, $cx + 15, 168, $skin);

            // Chest / Neckline
            imagefilledellipse($img, $cx, 172, 54, 30, $skin);

            // Face
            imagefilledellipse($img, $cx, 112, 70, 90, $skin);

            // Ears
            imagefilledellipse($img, $cx - 35, 114, 12, 18, $skin);
            imagefilledellipse($img, $cx + 35, 114, 12, 18, $skin);

            // Front Hair (Flowing side-swept hair and bangs)
            imagefilledellipse($img, $cx, 78, 78, 54, $darkNavy);

            // Left long strand overlaying shoulder
            imagefilledellipse($img, $cx - 34, 130, 32, 85, $darkNavy);
            // Right long strand overlaying shoulder
            imagefilledellipse($img, $cx + 34, 130, 32, 85, $darkNavy);

            // Side-swept Bangs
            $fBangs = [$cx - 35, 85, $cx + 5, 64, $cx + 30, 96];
            imagefilledpolygon($img, $fBangs, 3, $darkNavy);
        }

        header('Content-Type: image/png');
        imagepng($img);
        imagedestroy($img);
        exit;
    }
}
