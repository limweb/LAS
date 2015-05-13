<?php 
include_once __DIR__.'/las.php';

$las = new Las();
$las->readfile('example.las');
$las->output('test.las','F');
