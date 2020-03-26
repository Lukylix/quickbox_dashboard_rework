<?php
include_once('/srv/panel/inc/util.php');
include('/srv/panel/inc/services/model.php');

function processes($username)
{
  return shell_exec("ps -fu $username");
}

define('USERNAME', getUser());
define('USER_RUNNING', processes(USERNAME));

$servicesConfig = [
  ['bazarr', 'http' => true],
  ['btsync', 'url' => ':8888/gui', 'http' => true, 'psName' => 'resilio-sync', 'psUser' => 'rslsync', 'displayName' => 'BTSync'],
  ['couchpotato', 'displayName' => 'CouchPotato'],
  ['csf', 'url' => ':3443', 'psName' => 'lfd', 'psUser' => 'root', 'displayName' => 'CSF (firewall)'],
  //['deluge', 'process' => false],
  ['deluged', 'menu' => false, 'displayName' => 'DelugeD'],
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
  ['autodl', 'menu' => false, 'psName' => 'irssi', 'displayName' => 'AutoDL-irssi'],
  ['quassel', 'menu' => false],
  ['shellinabox', 'menu' => false, 'psUser' => 'shellinabox']
];
