<?php
ob_start();

include('inc/config/config.php');
include('inc/class/SQLSession.php');
include('inc/class/PasskeyAuth.php');

$auth = new PasskeyAuth();

$data = explode("&", $_GET['passkey']);

$passkey = $data[0];
$file = $data[1];

$data = explode("=", $file);

$file = $data[1];

isset($passkey) && $auth->verifyPasskey($passkey) ? null : exit;

isset($file) ? null : exit;

define('CHUNK_SIZE', 1024*1024); // Size (in bytes) of tiles chunk

  // Read a file and display its content chunk by chunk
  function readfile_chunked($filename, $retbytes = TRUE) {
    $buffer = '';
    $cnt =0;
    // $handle = fopen($filename, 'rb');
    $handle = fopen($filename, 'rb');
    if ($handle === false) {
      return false;
    }
    while (!feof($handle)) {
      $buffer = fread($handle, CHUNK_SIZE);
      echo $buffer;
      ob_flush();
      flush();
      if ($retbytes) {
        $cnt += strlen($buffer);
      }
    }
    $status = fclose($handle);
    if ($retbytes && $status) {
      return $cnt; // return num. bytes delivered like readfile() does.
    }
    return $status;
  }


// We'll be outputting a video
header('Content-type: video:x-msvideo');

header('Content-Disposition: attachment; filename=' . basename($file));
header('Content-Length: ' . filesize($baseFolder . $file));

readfile_chunked($baseFolder . $file);

?>
