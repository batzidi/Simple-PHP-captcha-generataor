<?php
// Session is used to check the captcha when the form is submitted
session_start();
$_SESSION['digit'] = "";

// Setting image width and height.
// N.B. Image should no less than 60px in width and 40px inheight!
$imageWidth = 200;
$imageHeight = 80;

// Yeah i know you want to set values less than minimum and see what will happen!
// This is why this is here :)
if ($imageWidth < 60 || $imageHeight < 40) {
	die("Image width should be 60px or more and image height should be 40px or more!");
}

if ($image = @imagecreatetruecolor($imageWidth, $imageHeight) or die("Cannot load GD library!")) {

	// Seeting RGB values of the color
	$colorR = mt_rand(50, 200);
	$colorG = mt_rand(50, 200);
	$colorB = mt_rand(50, 200);

	// Add color for background - use with caution as at some point the code is not readable
	//$colorRs = $colorR - mt_rand(25, 40);
	//$colorGs = $colorG - mt_rand(25, 40);
	//$colorBs = $colorB - mt_rand(25, 40);
	//$imageBackground = imagecolorallocate($image, $colorRs, $colorGs, $colorBs);

	// Or leave it white
	$imageBackground = imagecolorallocate($image, 255, 255, 255);
	imagefill($image, 0, 0, $imageBackground);

	$shapeColor = imagecolorallocate($image, $colorR, $colorG, $colorB);

	// Pick what would you like to show: numbers, letters, special symbols
	$chars = "";
	$chars .= "0123456789";
	//$chars .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	//$chars .= "abcdefghijklmnopqrstuvwxyz";
	//$chars .= "/.,;:\'\"\\|()-*/+!@#\$%&^&";

	// Init temp var
	$digit = "";

	// Hard code number of symbols
	//$captchaSymbols = 4;

	// or choose random number of symbols when you preserve 50px space for each symbol
	// Pick either of the following two lines
	//$captchaSymbols = mt_rand(1, floor($imageWidth / 50));
	$captchaSymbols = floor($imageWidth / 50);

	// Pick random font from folder
	//$fontArr = glob(__DIR__ . "/path/to/folder/*.*");
	//$fontFace = $fontArr[mt_rand(0, count($fontArr) - 1)];

	// or use your favourite font
	$selectedFont = "fontname.ttf";
	$fontFace = __DIR__ . DIRECTORY_SEPARATOR . $selectedFont;

	// Make a safe space from image border to prevent symbols cut
	$border = mt_rand(5, $imageHeight / 10 < 5 ? 5 : $imageHeight / 10);

	// Calculate how much width of the safe image space can a letter occupy
	$letter = ($imageWidth - 2 * $border) / $captchaSymbols;

	for ($i = 0; $i < $captchaSymbols; $i++) {
		// Add symbol to this var which later will be assigned to $_SESSION variable
		$digit .= ($symbol = $chars[mt_rand(0, strlen($chars) - 1)]);

		// Set range of font size
		$minFontSize = 14;
		$maxFontSize = floor(($imageHeight / 2.5 > 30) ? 30 : $imageHeight / 2.5);
		$fontSize = mt_rand($minFontSize, $maxFontSize);

		// Set cerning of the letter
		$angle = mt_rand(-($border + 20), ($border + 20));

		// Set the start x,y position of the symbol
		$startX = mt_rand($i * $letter, ($i + 1) * $letter - $border / 2);
		$startY = mt_rand($fontSize + $border, $imageHeight - $border < $fontSize + $border ? $fontSize + $border : $imageHeight - $border);

		// Add symbol to the image
		imagettftext($image, $fontSize, $angle, $startX, $startY, $shapeColor, $fontFace, $symbol);
	}

	$shapes = $imageHeight / 15;
	for ($i = 0; $i < $shapes; $i++) {
		imagesetthickness($image, mt_rand(1, 2));
		imageline($image, mt_rand(1, $imageWidth), mt_rand(5, $imageHeight - 5), mt_rand(1, $imageWidth), mt_rand(1, $imageHeight - 5), $shapeColor);
		imageellipse($image, mt_rand(0, $imageWidth), mt_rand(0, $imageHeight), mt_rand(0, $imageWidth), mt_rand(0, $imageHeight), $shapeColor);
	}

	// This control shows what exactly the captcha is.
	// This is just FYI whether you see it correctly
	// On PROD this line should be deleted!!!
	//imagettftext($image, 10, 5, 5, $imageHeight, imagecolorallocate($image, 0, 0, 0), $fontFace, $digit);

	// Record digits in $_SESSION var and unset the temp var
	$_SESSION['digit'] = $digit;
	unset($digit);

	// Display image and clean up
	header("Content-type: image/png");
	imagepng($image);
	imagedestroy($image);
}