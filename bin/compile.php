<?php

$outputpts = getopt("", ["output:"]);
$output = $outputpts["output"];

@unlink($output);
$phar = new Phar($output);
$phar->setStub('<?php define("pocketmine\\\\PATH", "phar://". __FILE__ ."/"); require_once("phar://". __FILE__ ."Source Folder/src/pocketmine/PocketMine.php");  __HALT_COMPILER();');
$phar->setSignatureAlgorithm(Phar::SHA1);
$phar->startBuffering();
foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator("Source Folder/src")) as $file){
	if(!$file->isFile()) continue;
	$phar->addFile($file, $file);
}
foreach($phar as $finfo){
	if($finfo->getSize() > 524288) $finfo->compress(Phar::GZ);
}
$phar->stopBuffering();
echo "Created phar at " . realpath($output), PHP_EOL;

?>