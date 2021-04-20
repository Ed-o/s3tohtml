<?php

#################################################
##				S3toHtml
## 
## A php server to allow browsing of an AWS S3 bucket via web browser
## Repo : https://github.com/Ed-o/s3tohtml
## 

# This file has any config items for you to change like bucket name
require 'config.php';

# We will be using the AWS S3 PHP SDK V3 
# (see the readme file for some more info on this)
require 'vendor/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

# We will pass the URL we are trying to get into parameter 'q' (this is done by the htaccess redirection
# If we do not set anything lets start at the root of the S3 bucket site '/'
if (isset($_GET['q'])) {
	$url=$_GET['q'];
} else {
	$url="/";
}

# Create an S3 Client
try {
	$s3Client = new S3Client([
		'region' => "$region",
		'version' => '2006-03-01'
	]);
# Catch errors if they come up
} catch (S3Exception $e) {
	echo $e->getMessage() . "\n";
}

# Now lets pull the S3 list for what is at that location using the SDK
try {
	# Check if the object exists as a file
	$response = $s3Client->doesObjectExist($bucket, $url);

	if ($response == true) { # We have a file - lets display it 

		$result = $s3Client->getObject(array(
			'Bucket' => $bucket,
			'Key' => $url
		));

		# Output the type of file based on what we are looking at
		header("Content-Type: {$result['ContentType']}");
		# And retrun the contents of the file
		echo $result['Body'] . "\n";

	} else { # there is no file so lets assume this is a directory and display that for browsing

		# The directory browser didn't work if the path was '/' so we remove that if we are looking at the root
		if ( $url == "/" ) { $url = ""; }
		# Pull the list of directories and files from the location in $url
		$result = $s3Client->ListObjects(['Bucket' => "$bucket", 'Delimiter'=>'/', 'Prefix' => "$url"]);

		# Add the HTML for the page and table layout
		echo "<html><head><title>S3toHTML</title><link rel=\"stylesheet\" type=\"text/css\" href=\"/style.css\"></head><body>";
		echo "<h3>S3toHTML browser</h3><br /><br />";
		echo "<table id=\"cols\">";
		echo "<tr><th colspan=\"2\"><center>$bucket</center></th></tr>";

		# Start be displaying the directory names and make them into links so we can browse round
		foreach ($result["CommonPrefixes"] as $res) {
			echo "<tr><td><a href=\"/" . $res["Prefix"] . "\"><img src=\"/images/folder.png\"></a></td>";
			echo "<td><a href=\"/" . $res["Prefix"] . "\">" . $res["Prefix"] . "</a></td></tr>";
		}

		# and then if there are files inside this directory then show them as links too 
		if (isset ($result[@Contents])) {
			foreach ($result["Contents"] as $res) {
				if (substr($res["Key"], -5) == ".html") { 
					$imageicon="web.png";
				} else {
					$imageicon="file.png";
				}
				echo "<tr><td><a href=\"/" . $res["Key"] . "\"><img src=\"/images/$imageicon\"></a></td>";
				echo "<td><a href=\"/" . $res["Key"] . "\">" . $res["Key"] . "</a></td></tr>";
			}
		}
		echo "</table><br /></body></html>";
	}
# Catch errors if they come up
} catch (Aws\S3\Exception\S3Exception $e) {
	echo $e->getMessage() . "\n";
}
?>
