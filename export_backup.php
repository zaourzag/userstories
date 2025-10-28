<?php

// No layout includes: we need clean headers for download
require 'vendor/autoload.php';
include 'utils.php';

$vragenlijst = new vragenLijst();
$data = $vragenlijst->getVragenForBackupNoCode(); // id, vraag, naam, email only

$filename = 'backup_vragen_' . date('Ymd_His') . '.json';
$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// Send download headers
header('Content-Type: application/json; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($json));
echo $json;
?>