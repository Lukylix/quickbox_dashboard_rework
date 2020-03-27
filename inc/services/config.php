<?php
include_once('/srv/panel/inc/util.php');
include('/srv/panel/inc/services/model.php');

function processes($username)
{
  return shell_exec("ps -fu $username");
}
$master = file_get_contents('/srv/panel/db/master.txt');
$master = preg_replace(
  '/\s+/',
  '',
  $master
);

define('MASTERUSER', $master);
define('USERNAME', getUser());
define('ISADMIN', (MASTERUSER == USERNAME));

define('USER_RUNNING', processes(USERNAME));

function search($data, $find, $end)
{
  $pos1 = strpos($data, $find) + strlen($find);
  $pos2 = strpos($data, $end, $pos1);
  return substr($data, $pos1, $pos2 - $pos1);
}

$zconf = '/srv/panel/db/znc.txt';
if (file_exists($zconf)) {
  $zconf_data = file_get_contents($zconf);
  $zport = search($zconf_data, 'Port = ', "\n");
  $zssl = search($zconf_data, 'SSL = ', "\n");
}

$servicesConfig = [
  'bazarr' => [
    'http' => true
  ],
  'btsync' => [
    'displayName' => 'BTSync',
    'url' => ':8888/gui',
    'http' => true,
    'psName' => 'resilio-sync',
    'psUser' => 'rslsync'
  ],
  'couchpotato' => [
    'displayName' => 'CouchPotato'
  ],
  'csf' => [
    'displayName' => 'CSF (firewall)',
    'url' => ':3443',
    'psName' => 'lfd',
    'psUser' => 'root'
  ],
  'deluged' => [
    'menu' => false,
    'displayName' => 'DelugeD',
    'visibleForAll' => true
  ],
  'deluged-web' => [
    'displayName' => 'Deluge Web',
    'visibleForAll' => true
  ],
  'emby' => [
    'psName' => 'EmbyServer',
    'psUser' => 'emby',
    'serviceName' => 'emby-server'
  ],
  'filebrowser' => [
    'rootService' => true
  ],
  'flood' => [
    'visibleForAll' => true
  ],
  'headphones' => [
    'url' => '/headphones/home',
    'rootService' => true
  ],
  'jackett' => [],
  'lounge' => [
    'displayName' => 'The Lounge',
    'url' => '/irc',
    'psUser' => 'lounge',
    'visibleForAll' => true
  ],
  'lidarr' => [
    'http' => true
  ],
  'medusa' => [],
  'netdata' => [
    'psUser' => 'netdata'
  ],
  'nextcloud' => [
    'displayName' => 'NextCloud',
    'process' => false,
  ],
  'nzbget' => [
    'displayName' => 'NZBGet',
    'visibleForAll' => true
  ],
  'nzbhydra' => [
    'displayName' => 'NZBHydra'
  ],
  'plex' => [
    'url' => ':32400/web/',
    'http' => true,
    'psUser' => 'plex',
    'serviceName' => 'plexmediaserver'
  ],
  'tautulli' => [
    'psUser' => 'tautulli'
  ],
  'ombi' => [
    'rootService' => true
  ],
  'pyload' => [
    'displayName' => 'pyLoad',
    'url' => '/pyload/login'
  ],
  'radarr' => [
    'rootService' => true
  ],
  'rapidleech' => [
    'process' => false
  ],
  'rutorrent' => [
    'displayName' => 'ruTorrent',
    'process' => false,
    'visibleForAll' => true
  ],
  'rtorrent' => [
    'menu' => false,
    'visibleForAll' => true
  ],
  'sabnzbd' => [
    'displayName' => 'SABnzbd'
  ],
  'sickgear' => [
    'displayName' => 'SickGear'
  ],
  'sickchill' => [
    'displayName' => 'SickChill'
  ],
  'sonarr' => [
    'psName' => 'nzbdrone'
  ],
  'subsonic' => [
    'rootService' => true
  ],
  'syncthing' => [],
  'znc' => [
    'displayName' => 'ZNC',
    'url' => ":$zport",
    'http' => !$zssl
  ],
  'autodl' => [
    'displayName' => 'AutoDL-irssi',
    'menu' => false,
    'psName' => 'irssi',
    'visibleForAll' => true
  ],
  'quassel' => [
    'menu' => false
  ],
  'shellinabox' => [
    'menu' => false,
    'psUser' => 'shellinabox'
  ],
];

/**
 * Search the service form the config and return the service object
 */
function getService($name)
{
  if (isset($GLOBALS['servicesConfig'][$name])) {
    return new Service(array_merge($GLOBALS['servicesConfig'][$name], ['name' => $name]));
  }
  return false;
}
