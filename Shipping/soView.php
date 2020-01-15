<?php

?>

<!DOCTYPE>
<html>
<head>
	<title>Sales Order :: </title>
	<style type="text/css" link="tableStyle.css"></style>
</head>
<body>	

<div style="height:5em;"></div>
<?php
	include ("links.php");
?>
<div style="height:1em;"></div>
<?php
	echo $searchBox;
?>
<div style="height:1em;"></div>
<div id="searchResults">
<?php
	echo $htmlTable;
?>
</div>
<div style="height:1em;"></div>
<?php
	echo $pagination;
?>
</body>
</html>