<?php

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'voting';

$connect = mysqli_connect($host, $user, $password, $db) or
    die('connection failed');

function console_log($output, $with_script_tags = true) {
  $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .');';
  if ($with_script_tags) {
    $js_code = '<script>' . $js_code . '</script>';
  }
  echo $js_code;
}

function voteAllowed(): bool {
    $filePath = '../allow_voting.txt';
    if (file_exists($filePath)) {
        $fileContent = file_get_contents($filePath);
        return $fileContent == '1';
    } else {
        return false;
    }
}

function setVoteAllowed(bool $value): void {
    $filePath = '../allow_voting.txt';
    $file = fopen($filePath, 'w');
    if ($file) {
        fwrite($file, $value ? '1' : '0');
        fclose($file);
    }
}
?>
