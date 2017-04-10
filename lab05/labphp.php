<html>
<head>
<style>

	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine">
	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
	
</style>

</head>
<body>

<?php 
$today = getdate();
$y = $today['year'];
$birthday=substr($_POST["birthdayDate"],0,4);
if (($y - $birthday) < 13){
	echo "<div id='all' style='background-image:url(http://www.3danimationindia.com/wp-content/uploads/2014/05/twitter_bg.jpg);background-repeat:no-repeat;background-position:center;'>";	
}
else{
	if ($_POST["gender"] == "Male"){
		echo "<div id='all' style='background-image:url(CodeGeass.jpg);background-repeat:no-repeat;background-position:center;'>";
		
	}
	
	else{
		echo "<div id='all' style='background-image:url(http://hd-wall-papers.com/images/wallpapers/beautiful-background-pictures/beautiful-background-pictures-20.jpg);background-repeat:no-repeat;background-position:center;'>";
	}
}

$province = $_POST["province"];
echo "<div id='header' data-role='header'>";
echo "<h1>";
echo $province;
echo "</h1>";
echo "</div>";


$image = "$province.png";
// Read image path, convert to base64 encoding
$imageData = base64_encode(file_get_contents($image));

// Format the image SRC:  data:{mime};base64,{data};
$src = 'data: '.mime_content_type($image).';base64,'.$imageData;

// Echo out a sample image

echo "<p style='text-align:center'>";
echo '<img src="' . $src . '">';
echo "</p>";
echo "<br>";

$str = file_get_contents("$province.txt");
echo "<p style='text-align:center;'>$str</p>";



echo "</div>";
?>


</body>
</html>