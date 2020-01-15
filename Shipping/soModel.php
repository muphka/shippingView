<?php
Class SalesOrders{
	function getSOcontents($soNumber){
		//$path = "\\\\legacy\\prodscan\\CO SHIPPING\\";
		$path = "Z:\\";
		$rmaPath = "\\\\legacy\\prodscan\\CO SHIPPING\\RMA\\";

		switch(substr($soNumber, 0, 4)):
			case "0008":
				$path = $rmaPath . "RMA " .  $soNumber ."\\";
				break;
			default:
				$path = $path . "CO " . $soNumber ."\\";
				break;
		endswitch;
		echo $path;
		$dir_handle = opendir($path) or die("Can't read the directory."); //("Unable to open $path. $php_errormsg");

		$filesArray = array();
		$counter = 0;

		while ($file = readdir($dir_handle)){
			if($this->isFileAllowed((string)$file)){
				array_push($filesArray, (string)$file);
				$counter++;
			}
		}

		closedir($dir_handle);
		return $this->htmlTable($filesArray);
	}

	function isFileAllowed($fileName){
		$allowedFileExts = array("jpg", "txt");
		$fileName = explode(".", $fileName);
		end($fileName);
		$fileExt = array_pop($fileName);

		if(in_array(strtolower($fileExt), $allowedFileExts)){
			return true;
		}else{
			return false;
		}
	}

	function htmlTable($files){
		$htmlTable = "";
		foreach ($files as $key => $value) {
			$htmlTable .= "<span><a href=\"$value\" >$value</a></span>&nbsp;";
		}

		return $htmlTable;
	}
}
?>