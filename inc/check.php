<?php
error_reporting(1);
require_once('db.php');


function isExtensionAvailable($name)
{
  if (!extension_loaded($name)) {
    $response = false;
  } else {
    $response = true;
  }
  return $response;
}
function checkFolderPerm($name)
{
  $perm = substr(sprintf('%o', fileperms($name)), -4);
  if ($perm >= '0775') {
    $response = true;
  } else {
    $response = false;
  }
  return $response;
}
function tableRow($name, $details, $status)
{
  if ($status == '1') {
    $pr = '<i class="fas fa-check"></i>';
  } else {
    $pr = '<i class="fas fa-times"></i>';
  }
  echo "<tr><td>$name</td><td>$details</td><td>$pr</td></tr>";
}
function getWebURL()
{
  $base_url = (isset($_SERVER['HTTPS']) &&
    $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
  $tmpURL = dirname(__FILE__);
  $tmpURL = str_replace(chr(92), '/', $tmpURL);
  $tmpURL = str_replace($_SERVER['DOCUMENT_ROOT'], '', $tmpURL);
  $tmpURL = ltrim($tmpURL, '/');
  $tmpURL = rtrim($tmpURL, '/');
  $tmpURL = str_replace('install', '', $tmpURL);
  $base_url .= $_SERVER['HTTP_HOST'] . '/' . $tmpURL;
  if (substr("$base_url", -1 == "/")) {
    $base_url = substr("$base_url", 0, -1);
  }
  return $base_url;
}

function getStatus($arr)
{
  return true;
}

function replaceData($val, $arr)
{
  foreach ($arr as $key => $value) {
    $val = str_replace('{{' . $key . '}}', $value, $val);
  }
  return $val;
}
function setDataValue($val, $loc)
{
  $file = fopen($loc, 'w');
  fwrite($file, $val);
  fclose($file);
}
function sysInstall($sr, $pt)
{
  return true;
}
function importDatabase($pt)
{
  return true;
}
function setAdminEmail($pt)
{
  return true;
}
//------------->> Extension & Permission
$requiredServerExtensions = [
  'BCMath', 'Ctype', 'Fileinfo', 'JSON', 'Mbstring', 'OpenSSL', 'PDO', 'pdo_mysql', 'Tokenizer', 'XML', 'cURL',  'GD', 'gmp'
];

$folderPermissions = [
  'bootstrap/cache/', 'storage/', 'storage/app/', 'storage/framework/', 'storage/logs/'
];
//------------->> Extension & Permission

if (isset($_GET['action'])) {
  $action = $_GET['action'];
} else {
  $action = "";
}

function installNow()
{

    $alldata = $_POST;
    $db_name = $_POST['db_name'];
    $db_host = $_POST['db_host'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $email = $_POST['email'];
    $siteurl = getWebURL();
    $app_key = base64_encode(random_bytes(32));
    $envcontent = "
    APP_NAME=Laravel
    APP_ENV=local
    APP_KEY=base64:$app_key
    APP_DEBUG=false
    APP_URL=$siteurl
  
    LOG_CHANNEL=stack
  
    DB_CONNECTION=mysql
    DB_HOST=$db_host
    DB_PORT=3306
    DB_DATABASE=$db_name
    DB_USERNAME=$db_user
    DB_PASSWORD=$db_pass
  
    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    QUEUE_CONNECTION=sync
    SESSION_DRIVER=file
    SESSION_LIFETIME=120
  
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
  
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=null
    MAIL_FROM_NAME='${APP_NAME}'
  
    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=
  
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=mt1
  
    MIX_PUSHER_APP_KEY='${PUSHER_APP_KEY}'
    MIX_PUSHER_APP_CLUSTER='${PUSHER_APP_CLUSTER}'";
    $status = 'ok';
  
    $envpath = dirname(__DIR__, 1) . '/.env';
    file_put_contents($envpath, $envcontent);
    if ($status == 'ok') {
      if (importDatabase($alldata)) {  
        $query = file_get_contents("database.sql");
        $stmt = db_query($query);
      }
  
      if (setAdminEmail($alldata)) {
        $sql = "UPDATE admins SET email='" . $email . "' WHERE username='admin'";
        $stmt = $stmt = db_query($sql);
        return '<p class="text-success warning">Email address of admin has been set successfully!</p><div class="warning">
          <p class="text-danger lead my-3">Please delete the "install" folder from the server.</p>
          <p class="text-warning lead my-3">Please change the admin password as soon as possible.</p>
          </div><div class="warning">
          <a href="' . getWebURL() . '" class="theme-button choto">Go to website</a>
          <a href="' . getWebURL() . '/admin" class="theme-button choto">Go to Admin Panel</a>
          </div>';
      }
    }
  
}


function table($requiredServerExtensions)
{
  $error = 0;
  $phpversion = version_compare(PHP_VERSION, '7.3', '>=');
  if ($phpversion == true) {
    $error = $error + 0;
    tableRow("PHP", "Required PHP version 7.3 or higher", 1);
  } else {
    $error = $error + 1;
    tableRow("PHP", "Required PHP version 7.3 or higher", 0);
  }
  foreach ($requiredServerExtensions as $key) {
    $extension = isExtensionAvailable($key);
    if ($extension == true) {
      tableRow($key, "Required " . strtoupper($key) . " PHP Extension", 1);
    } else {
      $error += 1;
      tableRow($key, "Required " . strtoupper($key) . " PHP Extension", 0);
    }
  }
}

function folderPermissions($folderPermissions)
{
  $error = 0;
  foreach ($folderPermissions as $key) {
    $folder_perm = checkFolderPerm($key);
    if (checkFolderPerm($key) != true) {
      $error += 1;
      tableRow(str_replace("../", "", $key)," Required permission: 0775 ",0);
    }
  }

  if (!file_exists('database.sql')) {
    $error = $error+1;
    tableRow('Database',' Required "database.sql" available',0);
  } else {
    $error = $error+0;
    tableRow('Database',' Required "database.sql" available', 1);
  }
  
  if (!file_exists('../.htaccess')) {
    $error = $error+1;
    tableRow('.htaccess',' Required ".htaccess" available',0);
  }
}
