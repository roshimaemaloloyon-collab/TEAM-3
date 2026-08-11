<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PositionPhotoController extends Controller
{
    public function show(string $role)
    {
        $roleKey = strtoupper(str_replace('-', ' ', $role));
        $publicDir = public_path('images/positions/');

        if ($roleKey === 'MC TAXI DRIVER') {
            $mcPath = $publicDir . 'mc_taxi_driver.jpg';
            if (file_exists($mcPath)) {
                return response()->file($mcPath, ['Content-Type' => 'image/jpeg']);
            }
        }

        if ($roleKey === '4 WHEEL CAR DRIVER' || $roleKey === '4-WHEEL CAR DRIVER') {
            $carPathJpg = $publicDir . '4wheel_car_driver.jpg';
            if (file_exists($carPathJpg)) {
                return response()->file($carPathJpg, ['Content-Type' => 'image/jpeg']);
            }
        }

        if ($roleKey === 'OPERATIONS MANAGER') {
            $opsPath = $publicDir . 'operations_manager.jpg';
            if (file_exists($opsPath)) {
                return response()->file($opsPath, ['Content-Type' => 'image/jpeg']);
            }
        }

        if ($roleKey === 'OFFICE STAFF') {
            $staffPath = $publicDir . 'office_staff.jpg';
            if (file_exists($staffPath)) {
                return response()->file($staffPath, ['Content-Type' => 'image/jpeg']);
            }
        }

        $imageMap = [
            'MC TAXI DRIVER'         => $publicDir . 'mc_taxi_driver.jpg',
            '4-WHEEL CAR DRIVER'     => $publicDir . '4wheel_car_driver.jpg',
            'OPERATIONS MANAGER'     => $publicDir . 'operations_manager.jpg',
            'OFFICE STAFF'           => $publicDir . 'office_staff.jpg',
            'HR MANAGER'             => $publicDir . 'position_hr_manager.png',
            'FACILITIES COORDINATOR' => $publicDir . 'position_facilities_coordinator.png',
            'VEHICLE DISPATCHER'     => $publicDir . 'position_operations_manager.png',
            'FINANCE OFFICER'        => $publicDir . 'position_office_staff.png',
            'RECRUITMENT SPECIALIST' => $publicDir . 'position_hr_manager.png',
        ];

        if (isset($imageMap[$roleKey]) && file_exists($imageMap[$roleKey])) {
            return response()->file($imageMap[$roleKey], ['Content-Type' => 'image/png']);
        }

        // --- DYNAMIC GD IMAGE GENERATOR ---
        $width = 500;
        $height = 360;
        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        // Soft warm background
        $bg = imagecolorallocate($image, 248, 246, 240);
        imagefilledrectangle($image, 0, 0, $width, $height, $bg);

        // Building / Background Accent Silhouette
        $buildingColor = imagecolorallocate($image, 235, 230, 220);
        imagefilledrectangle($image, 20, 100, 110, 360, $buildingColor);
        imagefilledrectangle($image, 130, 70, 220, 360, $buildingColor);
        imagefilledrectangle($image, 310, 90, 390, 360, $buildingColor);
        imagefilledrectangle($image, 410, 120, 480, 360, $buildingColor);

        // Color Palette
        $darkNavy   = imagecolorallocate($image, 20, 35, 60);
        $navyLight  = imagecolorallocate($image, 35, 65, 110);
        $accentBlue = imagecolorallocate($image, 50, 120, 230);
        $skinTone   = imagecolorallocate($image, 238, 195, 154);
        $skinDark   = imagecolorallocate($image, 130, 80, 50); // Dark skin tone para sa Dispatcher
        $hairDark   = imagecolorallocate($image, 30, 25, 25);
        $hairBrown  = imagecolorallocate($image, 80, 50, 35);
        $whiteColor = imagecolorallocate($image, 255, 255, 255);
        $mapRoad    = imagecolorallocate($image, 220, 215, 200);
        $mapRoute   = imagecolorallocate($image, 230, 160, 40);

        switch ($roleKey) {
            case '4-WHEEL CAR DRIVER': // Batay sa ginawa mong sasakyan
                // White Sedan Car Drawing
                imagefilledrectangle($image, 80, 180, 420, 280, $whiteColor); // Car body
                imagefilledrectangle($image, 140, 120, 360, 180, $whiteColor); // Cabin top
                imagefilledrectangle($image, 150, 130, 350, 175, $accentBlue); // Glass windshield
                imagefilledellipse($image, 150, 280, 80, 80, $darkNavy); // Front wheel
                imagefilledellipse($image, 350, 280, 80, 80, $darkNavy); // Rear wheel
                imagefilledellipse($image, 150, 280, 40, 40, $bg); // Rim
                imagefilledellipse($image, 350, 280, 40, 40, $bg); // Rim
                break;

            case 'VEHICLE DISPATCHER': // Batay sa Image 7840
                // Dispatcher na nakatitig sa 4 GPS Monitors
                // 4 Monitors
                imagefilledrectangle($image, 240, 60, 360, 140, $darkNavy); // Top Left
                imagefilledrectangle($image, 245, 65, 355, 135, $whiteColor);
                imagefilledrectangle($image, 370, 60, 490, 140, $darkNavy); // Top Right
                imagefilledrectangle($image, 375, 65, 485, 135, $whiteColor);
                imagefilledrectangle($image, 240, 150, 360, 230, $darkNavy); // Bottom Left
                imagefilledrectangle($image, 245, 155, 355, 225, $whiteColor);
                imagefilledrectangle($image, 370, 150, 490, 230, $darkNavy); // Bottom Right
                imagefilledrectangle($image, 375, 155, 485, 225, $whiteColor);

                // GPS Route lines on screens
                imageline($image, 250, 100, 350, 100, $mapRoute);
                imageline($image, 380, 80, 480, 120, $mapRoute);

                // Headset & Dispatcher Avatar (Profile side view)
                imagefilledrectangle($image, 60, 210, 190, 360, $navyLight); // Navy Shirt
                imagefilledellipse($image, 150, 150, 80, 95, $skinDark); // Face side
                imagefilledellipse($image, 135, 120, 85, 65, $hairDark); // Hair
                imagearc($image, 140, 145, 75, 80, 180, 360, $darkNavy); // Headset Band
                imagefilledellipse($image, 110, 150, 25, 25, $darkNavy); // Ear cup
                imageline($image, 110, 150, 160, 175, $darkNavy); // Mic
                break;

            case 'RECRUITMENT SPECIALIST': // Batay sa Image 7839
                // Receptionist/HR holding Applicant CV Clipboard
                imagefilledrectangle($image, 170, 190, 310, 360, $whiteColor); // White Collared Shirt
                imagefilledellipse($image, 240, 135, 75, 90, $skinTone); // Face
                imagefilledellipse($image, 240, 100, 85, 55, $hairBrown); // Brown Bun Hair
                
                // Clipboard sa kamay (CV with Photo)
                imagefilledrectangle($image, 280, 180, 420, 320, $darkNavy); // Board
                imagefilledrectangle($image, 288, 190, 412, 312, $whiteColor); // Paper
                imagefilledrectangle($image, 300, 205, 330, 240, $accentBlue); // Candidate Photo
                imagefilledrectangle($image, 340, 210, 400, 215, $darkNavy); // Text line
                imagefilledrectangle($image, 340, 225, 390, 230, $darkNavy); // Text line
                break;

            case 'FINANCE OFFICER': // Batay sa Image 7838
                // Formal Woman with Dark Suit holding Laptop / Documents
                imagefilledrectangle($image, 170, 170, 330, 360, $darkNavy); // Navy Blazer Suit
                imagefilledellipse($image, 250, 130, 80, 95, $skinTone); // Face
                imagefilledellipse($image, 250, 95, 90, 70, $hairDark); // Long Dark Hair
                
                // Holding Laptop
                imagefilledrectangle($image, 200, 240, 320, 310, $navyLight); // Laptop Gray/Navy Cover
                break;

            case 'HR MANAGER':
                imagefilledrectangle($image, 170, 170, 330, 360, $darkNavy);
                imagefilledellipse($image, 250, 130, 80, 95, $skinTone);
                imagefilledellipse($image, 250, 95, 90, 70, $hairDark);
                break;

            default:
                // Default Canvas Accent Circle
                imagefilledellipse($image, 250, 180, 120, 120, $accentBlue);
                break;
        }

        // Return generated GD PNG Image directly
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
        exit;
    }
}