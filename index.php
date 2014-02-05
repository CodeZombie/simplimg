<?php
##
##written by Cazum (zombiearmy.net) in Feb, 2014
##Only the first line needs to be edited to function properly
##

//define the directory where images will be stored, relative to the position of this file.
DEFINE("IMAGE_DIRECTORY","images/");

//check if the header contains an image request
if(!isset($_GET['i'])) {
	die("no file selected");
}

//retrieve the requested file-name
$requested_filename = $_GET['i'];

//##retrieve an array consisting of all possible files in our image directory
//read all files in the directory
$files_in_directory = scandir(IMAGE_DIRECTORY);

//strip the . and .. results which are not image files
foreach($files_in_directory as $key => $value) {
	if($value == "." || $value == "..") {
		unset($files_in_directory[$key]);
	}
}
//re-orders the arrays keys
$files_in_directory = array_values($files_in_directory);

//##cycle through each value in array and check against the requested file-name
//create a flag which will go up if the foreach concludes with no match
$failed_to_match = false;

//cycle through each file in the directory, and search for a match
foreach($files_in_directory as $file) {
	if($file == $requested_filename) {
		$failed_to_match = false;
		break;
	}
	$failed_to_match = true;
}

//if no match was found
if($failed_to_match) {
	die("file does not exist");
}

//prepare output data
$data = array();
$data["filename"] = $requested_filename;
$data["filepath"] = IMAGE_DIRECTORY . $requested_filename;
$data["lastmodified"] = date('M jS, Y \a\t g:ia', filemtime($data["filepath"]));
$data["dimensions"] = getimagesize($data["filepath"])[0] . "x" . getimagesize($data["filepath"])[1];

//gets the file size in bytes
$filesize = filesize($data["filepath"]);

//##convert the file-size to a human-readable format
//convert to byte
if($filesize < 770) {
	$data["filesize"] = round($filesize,2) . "bytes";
}

//convert to kb
if($filesize >= 770 && $filesize < 786432) {
	$data["filesize"] = round($filesize/1024,2) . "kb";
}
//convert to mb
if($filesize >= 786432) {
	$data["filesize"] = round($filesize/1048576,2) . "mb";
}
?>
<html>
    <head>
		<style type="text/css">
			.info{
				height:13px;
				font-size:13px;
			}
			.descript{
				color:green;
			}
		</style>
	</head>
    <body>
		<img src="<?php echo $data["filepath"]; ?>"></img>
		<div class="info"><span class="descript">filename: </span><?php echo $data["filename"]; ?></div>
		<div class="info"><span class="descript">file size: </span><?php echo $data["filesize"]; ?></div>
		<div class="info"><span class="descript">last modified: </span><?php echo $data["lastmodified"]; ?></div>
		<div class="info"><span class="descript">image dimensions: </span><?php echo $data["dimensions"]; ?></div>
    </body>
</html>