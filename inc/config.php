<?php
if (isset($_SESSION)) {
  session_destroy();
}

include '/srv/panel/inc/util.php';
include($_SERVER['DOCUMENT_ROOT'] . '/widgets/class.php');
$version = "v1.2.0";
error_reporting(E_ERROR);
$master = file_get_contents('/srv/panel/db/master.txt');
$master = preg_replace('/\s+/', '', $master);
$username = getUser();

require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/localize.php');

// Network Interface
$interface = 'eno1';
$iface_list = array('eno1');
$iface_title['eno1'] = 'External';
$vnstat_bin = '/usr/bin/vnstat';
$data_dir = './dumps';
$byte_notation = null;

$zconf = '/srv/panel/db/znc.txt';
if (file_exists($zconf)) {
  $zconf_data = file_get_contents($zconf);
  $zport = search($zconf_data, 'Port = ', "\n");
  $zssl = search($zconf_data, 'SSL = ', "\n");
}


function search($data, $find, $end)
{
  $pos1 = strpos($data, $find) + strlen($find);
  $pos2 = strpos($data, $end, $pos1);
  return substr($data, $pos1, $pos2 - $pos1);
}

define('HTTP_HOST', preg_replace('~^www\.~i', '', $_SERVER['HTTP_HOST']));

$panel = array(
  'name'              => 'swizzin Seedbox',
  'author'            => 'liaralabs',
  'robots'            => 'noindex, nofollow',
  'title'             => 'swizzin dash',
  'description'       => 'swizzin is an open-source seedbox project check out https://github.com/liaralabs/swizzin for more info',
  'active_page'       => basename($_SERVER['PHP_SELF']),
);

$time_start = microtime_float();

// Timing
function microtime_float()
{
  $mtime = microtime();
  $mtime = explode(' ', $mtime);
  return $mtime[1] + $mtime[0];
}

//Unit Conversion
function formatsize($size)
{
  $danwei = array(' B ', ' KB ', ' MB ', ' GB ', ' TB ');
  $allsize = array();
  $i = 0;
  for ($i = 0; $i < 5; $i++) {
    if (floor($size / pow(1024, $i)) == 0) {
      break;
    }
  }
  for ($l = $i - 1; $l >= 0; $l--) {
    $allsize1[$l] = floor($size / pow(1024, $l));
    $allsize[$l] = $allsize1[$l] - $allsize1[$l + 1] * 1024;
  }
  $len = count($allsize);
  for ($j = $len - 1; $j >= 0; $j--) {
    $fsize .= $allsize[$j] . $danwei[$j];
  }
  return $fsize;
}

function GetCoreInformation()
{
  $data = file('/proc/stat');
  $cores = array();
  foreach ($data as $line) {
    if (preg_match('/^cpu[0-9]/', $line)) {
      $info = explode(' ', $line);
      $cores[] = array('user' => $info[1], 'nice' => $info[2], 'sys' => $info[3], 'idle' => $info[4], 'iowait' => $info[5], 'irq' => $info[6], 'softirq' => $info[7]);
    }
  }
  return $cores;
}
function GetCpuPercentages($stat1, $stat2)
{
  if (count($stat1) !== count($stat2)) {
    return;
  }
  $cpus = array();
  for ($i = 0, $l = count($stat1); $i < $l; $i++) {
    $dif = array();
    $dif['user'] = $stat2[$i]['user'] - $stat1[$i]['user'];
    $dif['nice'] = $stat2[$i]['nice'] - $stat1[$i]['nice'];
    $dif['sys'] = $stat2[$i]['sys'] - $stat1[$i]['sys'];
    $dif['idle'] = $stat2[$i]['idle'] - $stat1[$i]['idle'];
    $dif['iowait'] = $stat2[$i]['iowait'] - $stat1[$i]['iowait'];
    $dif['irq'] = $stat2[$i]['irq'] - $stat1[$i]['irq'];
    $dif['softirq'] = $stat2[$i]['softirq'] - $stat1[$i]['softirq'];
    $total = array_sum($dif);
    $cpu = array();
    foreach ($dif as $x => $y) $cpu[$x] = round($y / $total * 100, 2);
    $cpus['cpu' . $i] = $cpu;
  }
  return $cpus;
}
$stat1 = GetCoreInformation();
sleep(1);
$stat2 = GetCoreInformation();
$data = GetCpuPercentages($stat1, $stat2);
$cpu_show = $data['cpu0']['user'] . "%us,  " . $data['cpu0']['idle'] . "%id,  ";

// Information obtained depending on the system CPU
switch (PHP_OS) {
  case "Linux":
    $sysReShow = (false !== ($sysInfo = sys_linux())) ? "show" : "none";
    break;

  case "FreeBSD":
    $sysReShow = (false !== ($sysInfo = sys_freebsd())) ? "show" : "none";
    break;
}

//linux system detects
function sys_linux()
{
  // CPU
  if (false === ($str = @file("/proc/cpuinfo"))) return false;
  $str = implode("", $str);
  @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([^\:]+)([\r\n]+)/s", $str, $model);
  @preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);
  @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);
  if (false !== is_array($model[1])) {
    $res['cpu']['num'] = sizeof($model[1]);

    if ($res['cpu']['num'] == 1)
      $x1 = '';
    else
      $x1 = ' ×' . $res['cpu']['num'];
    $mhz[1][0] = ' <span style="color:#999;font-weight:600">Frequency:</span> ' . $mhz[1][0];
    $cache[1][0] = ' <br /> <span style="color:#999;font-weight:600">Secondary cache:</span> ' . $cache[1][0];
    $res['cpu']['model'][] = '<h4>' . $model[1][0] . '</h4>' . $mhz[1][0] . $cache[1][0] . $x1;
    if (false !== is_array($res['cpu']['model'])) $res['cpu']['model'] = implode("<br />", $res['cpu']['model']);
    if (false !== is_array($res['cpu']['mhz'])) $res['cpu']['mhz'] = implode("<br />", $res['cpu']['mhz']);
    if (false !== is_array($res['cpu']['cache'])) $res['cpu']['cache'] = implode("<br />", $res['cpu']['cache']);
  }

  return $res;
}

//FreeBSD system detects
function sys_freebsd()
{
  //CPU
  if (false === ($res['cpu']['num'] = get_key("hw.ncpu"))) return false;
  $res['cpu']['model'] = get_key("hw.model");
  return $res;
}

//Obtain the parameter values FreeBSD
function get_key($keyName)
{
  return do_command('sysctl', "-n $keyName");
}

//Determining the location of the executable file FreeBSD
function find_command($commandName)
{
  $path = array('/bin', '/sbin', '/usr/bin', '/usr/sbin', '/usr/local/bin', '/usr/local/sbin');
  foreach ($path as $p) {
    if (@is_executable("$p/$commandName")) return "$p/$commandName";
  }
  return false;
}

//Order Execution System FreeBSD
function do_command($commandName, $args)
{
  $buffer = "";
  if (false === ($command = find_command($commandName))) return false;
  if ($fp = @popen("$command $args", 'r')) {
    while (!@feof($fp)) {
      $buffer .= @fgets($fp, 4096);
    }
    return trim($buffer);
  }
  return false;
}


function GetWMI($wmi, $strClass, $strValue = array())
{
  $arrData = array();

  $objWEBM = $wmi->Get($strClass);
  $arrProp = $objWEBM->Properties_;
  $arrWEBMCol = $objWEBM->Instances_();
  foreach ($arrWEBMCol as $objItem) {
    @reset($arrProp);
    $arrInstance = array();
    foreach ($arrProp as $propItem) {
      $value = $objItem->{$propItem->Name};
      if (empty($strValue)) {
        $arrInstance[$propItem->Name] = trim($value);
      } else {
        if (in_array($propItem->Name, $strValue)) {
          $arrInstance[$propItem->Name] = trim($value);
        }
      }
    }
    $arrData[] = $arrInstance;
  }
  return $arrData;
}

//NIC flow
$strs = @file("/proc/net/dev");

for ($i = 2; $i < count($strs); $i++) {
  preg_match_all("/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info);
  $NetOutSpeed[$i] = $info[10][0];
  $NetInputSpeed[$i] = $info[2][0];
  $NetInput[$i] = formatsize($info[2][0]);
  $NetOut[$i]  = formatsize($info[10][0]);
}

//Real-time refresh ajax calls
if ($_GET['act'] == "rt") {
  $arr = array('NetOut2' => "$NetOut[2]", 'NetOut3' => "$NetOut[3]", 'NetOut4' => "$NetOut[4]", 'NetOut5' => "$NetOut[5]", 'NetOut6' => "$NetOut[6]", 'NetOut7' => "$NetOut[7]", 'NetOut8' => "$NetOut[8]", 'NetOut9' => "$NetOut[9]", 'NetOut10' => "$NetOut[10]", 'NetInput2' => "$NetInput[2]", 'NetInput3' => "$NetInput[3]", 'NetInput4' => "$NetInput[4]", 'NetInput5' => "$NetInput[5]", 'NetInput6' => "$NetInput[6]", 'NetInput7' => "$NetInput[7]", 'NetInput8' => "$NetInput[8]", 'NetInput9' => "$NetInput[9]", 'NetInput10' => "$NetInput[10]", 'NetOutSpeed2' => "$NetOutSpeed[2]", 'NetOutSpeed3' => "$NetOutSpeed[3]", 'NetOutSpeed4' => "$NetOutSpeed[4]", 'NetOutSpeed5' => "$NetOutSpeed[5]", 'NetInputSpeed2' => "$NetInputSpeed[2]", 'NetInputSpeed3' => "$NetInputSpeed[3]", 'NetInputSpeed4' => "$NetInputSpeed[4]", 'NetInputSpeed5' => "$NetInputSpeed[5]");
  $jarr = json_encode($arr);
  $_GET['callback'] = htmlspecialchars($_GET['callback']);
  echo $_GET['callback'], '(', $jarr, ')';
  exit;
}

function session_start_timeout($timeout = 5, $probability = 100, $cookie_domain = '/')
{
  ini_set("session.gc_maxlifetime", $timeout);
  ini_set("session.cookie_lifetime", $timeout);
  $seperator = strstr(strtoupper(substr(PHP_OS, 0, 3)), "WIN") ? "\\" : "/";
  $path = ini_get("session.save_path") . $seperator . "session_" . $timeout . "sec";
  if (!file_exists($path)) {
    if (!mkdir($path, 600)) {
      trigger_error("Failed to create session save path directory '$path'. Check permissions.", E_USER_ERROR);
    }
  }
  ini_set("session.save_path", $path);
  ini_set("session.gc_probability", $probability);
  ini_set("session.gc_divisor", 100);
  session_start();
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), $_COOKIE[session_name()], time() + $timeout, $cookie_domain);
  }
}

session_start_timeout(5);
$MSGFILE = session_id();


function processes($username)
{
  return shell_exec("ps -fu $username");
}
define('USERNAME', getUser());
define('USER_RUNNING', processes($username));
include($_SERVER['DOCUMENT_ROOT'] . '/inc/service_model.php');

$servicesConfig = [
  ['bazarr', 'http' => true],
  ['btsync', 'url' => ':8888/gui', 'http' => true, 'psName' => 'resilio-sync', 'psUser' => 'rslsync', 'displayName' => 'BTSync'],
  ['couchpotato', 'displayName' => 'CouchPotato'],
  ['csf', 'url' => ':3443', 'psName' => 'lfd', 'psUser' => 'root', 'displayName' => 'CSF (firewall)'],
  //['deluge', 'process' => false],
  ['deluged', 'menu' => false, 'displayName'=> 'DelugeD'],
  ['deluged-web', 'displayName' => 'Deluge Web'],
  ['emby', 'psName' => 'EmbyServer', 'psUser' => 'emby'],
  ['filebrowser'],
  ['flood'],
  ['headphones', 'url' => '/headphones/home'],
  ['jackett'],
  ['lounge', 'url' => '/irc', 'psUser' => 'lounge', 'displayName' => 'The Lounge'],
  ['lidarr', 'http' => true],
  ['medusa'],
  ['netdata', 'psUser' => 'netdata'],
  ['nextcloud', 'process' => false, 'displayName' => 'NextCloud'],
  ['nzbget', 'displayName' => 'NZBGet'],
  ['nzbhydra', 'displayName' => 'NZBHydra'],
  ['plex', 'url' => ':32400/web/', 'http' => true, 'psName' => 'Plex', 'psUser' => 'plex'],
  ['tautulli', 'psName' => 'Tautulli', 'psUser' => 'tautulli'],
  ['ombi'],
  ['pyload', 'url' => '/pyload/login', 'displayName' => 'pyLoad'],
  ['radarr'],
  ['rapidleech', 'process' => false],
  ['rutorrent', 'process' => false, 'displayName' => 'ruTorrent'],
  ['rtorrent', 'menu' => false],
  ['sabnzbd', 'displayName' => 'SABnzbd'],
  ['sickgear', 'displayName' => 'SickGear'],
  ['sickchill', 'displayName' => 'SickChill'],
  ['sonarr', 'psName' => 'nzbdrone'],
  ['subsonic'],
  ['syncthing'],
  ['znc', 'url' => ":$zport", 'http' => $zssl ? false : true, 'displayName' => 'ZNC'],
  ['autodl' ,'menu' => false, 'psName'=> 'irssi', 'displayName'=> 'AutoDL-irssi'],
  ['quassel', 'menu' => false],
  ['shellinabox', 'menu' => false, 'psUser' => 'shellinabox']
];

// $services is a tab of all Service object for vues / logic
$services = [];
foreach ($servicesConfig as $configData) {
  $services[$configData[0]] = new Service($configData);
}

include($_SERVER['DOCUMENT_ROOT'] . '/widgets/lang_select.php');
include($_SERVER['DOCUMENT_ROOT'] . '/widgets/plugin_data.php');
include($_SERVER['DOCUMENT_ROOT'] . '/widgets/package_data.php');
include($_SERVER['DOCUMENT_ROOT'] . '/widgets/sys_data.php');
include($_SERVER['DOCUMENT_ROOT'] . '/widgets/theme_select.php');
$base = 1024;
$location = "/home";

/* check for services */
switch (intval($_GET['id'])) {
  
    /* enable & start services */
  case 66:
    $process = $_GET['serviceenable'];
    if ($process == "filebrowser") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl start $process");
    } elseif ($process == "resilio-sync") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl start $process");
    } elseif ($process == "shellinabox") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl start $process");
    } elseif ($process == "emby-server") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl start $process");
    } elseif ($process == "headphones") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl start $process");
    } elseif ($process == "medusa") {
      shell_exec("sudo systemctl disable sickchill@$username");
      shell_exec("sudo systemctl stop sickchill@$username");
      shell_exec("sudo systemctl disable sickgear@$username");
      shell_exec("sudo systemctl stop sickgear@$username");
      shell_exec("sudo systemctl enable $process@$username");
      shell_exec("sudo systemctl start $process@$username");
    } elseif ($process == "netdata") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl start $process");
    } elseif ($process == "nzbget") {
      shell_exec("sudo systemctl enable $process@$username");
      shell_exec("sudo systemctl start $process@$username");
    } elseif ($process == "plexmediaserver") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl start $process");
    } elseif ($process == "tautulli") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl start $process");
    } elseif ($process == "ombi") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl start $process");
    } elseif ($process == "radarr") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl start $process");
    } elseif ($process == "sickgear") {
      shell_exec("sudo systemctl disable medusa@$username");
      shell_exec("sudo systemctl stop medusa@$username");
      shell_exec("sudo systemctl disable sickchill@$username");
      shell_exec("sudo systemctl stop sickchill@$username");
      shell_exec("sudo systemctl enable $process@$username");
      shell_exec("sudo systemctl start $process@$username");
    } elseif ($process == "sickchill") {
      shell_exec("sudo systemctl disable medusa@$username");
      shell_exec("sudo systemctl stop medusa@$username");
      shell_exec("sudo systemctl disable sickgear@$username");
      shell_exec("sudo systemctl stop sickgear@$username");
      shell_exec("sudo systemctl enable $process@$username");
      shell_exec("sudo systemctl start $process@$username");
    } elseif ($process == "subsonic") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl start $process");
    } else {
      shell_exec("sudo systemctl enable $process@$username");
      shell_exec("sudo systemctl start $process@$username");
    }
    header('Location: https://' . $_SERVER['HTTP_HOST'] . '/');
    break;

    /* disable & stop services */
  case 77:
    $process = $_GET['servicedisable'];
    if ($process == "filebrowser") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } elseif ($process == "resilio-sync") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } elseif ($process == "shellinabox") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } elseif ($process == "emby-server") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } elseif ($process == "headphones") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } elseif ($process == "lounge") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } elseif ($process == "netdata") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } elseif ($process == "plexmediaserver") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } elseif ($process == "tautulli") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } elseif ($process == "ombi") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } elseif ($process == "radarr") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } elseif ($process == "subsonic") {
      shell_exec("sudo systemctl stop $process");
      shell_exec("sudo systemctl disable $process");
    } else {
      shell_exec("sudo systemctl stop $process@$username");
      shell_exec("sudo systemctl disable $process@$username");
    }
    header('Location: https://' . $_SERVER['HTTP_HOST'] . '/');
    break;

    /* restart services */
  case 88:
    $process = $_GET['servicestart'];
    if ($process == "filebrowser") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } elseif ($process == "resilio-sync") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } elseif ($process == "shellinabox") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } elseif ($process == "emby-server") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } elseif ($process == "headphones") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } elseif ($process == "lounge") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } elseif ($process == "netdata") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } elseif ($process == "plexmediaserver") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } elseif ($process == "tautulli") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } elseif ($process == "ombi") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } elseif ($process == "radarr") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } elseif ($process == "subsonic") {
      shell_exec("sudo systemctl enable $process");
      shell_exec("sudo systemctl restart $process");
    } else {
      shell_exec("sudo systemctl restart $process@$username");
    }
    header('Location: https://' . $_SERVER['HTTP_HOST'] . '/');
    break;
}
