<?php
require __DIR__ . '/vendor/autoload.php';
use Google\Cloud\Vision\VisionClient;

if (!isset($argv[1])) exit("argv1 is required.\n");
if (!file_exists($argv[1])) exit($argv[1] . " does not exists.\n");

$vision = new VisionClient();

$resource = file_get_contents($argv[1]);
$image = $vision->image($resource, ['TEXT_DETECTION']);
$annotation = $vision->annotate($image);

$txt = $annotation->fullText()->text();

var_dump($txt);