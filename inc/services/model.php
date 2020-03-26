<?php
class Service
{
  private $name;
  private $displayName;
  private $menu = true;
  private $http = false;
  private $url;
  private $process = true;
  private $psName;
  private $psUser;
  private $exist = false;

  function __construct(array $data)
  {
    $this->hydrate($data);
    $this->default();
    if ($this->menu) $this->url = ($this->http ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $this->url;
    if ($this->process) {
      $this->processExists();
    }
  }

  private function processExists()
  {
    if (!isset($this->psUser) || $this->psUser == '') {
      if (stripos(USER_RUNNING, $this->psName) !== false) $this->exist = true;
    } else {
      exec("ps -fu " . $this->psUser . " | grep -iE " . $this->psName . " | grep -v grep", $pids);
      if (count($pids) > 0) $this->exist = true;
    }
  }

  //Call this function if you need to display html
  function getHtmlSwitch()
  {
    if (file_exists('/etc/systemd/system/multi-user.target.wants/' . $this->psName . '@' . (isset($this->psUser) && $this->psUser != '' ? $this->psUser : USERNAME) . '.service') || file_exists('/etc/systemd/system/multi-user.target.wants/' .  $this->psName . '.service')) {
      return " <div class=\"toggle-wrapper text-center\"> <div class=\"toggle-en toggle-light primary\" onclick=\"location.href='?id=77&servicedisable=" . $this->psName . "'\"></div></div>";
    } else {
      return " <div class=\"toggle-wrapper text-center\"> <div class=\"toggle-dis toggle-light primary\" onclick=\"location.href='?id=66&serviceenable=" . $this->psName . "'\"></div></div>";
    }
  }

  private function
  default()
  {
    if (!isset($this->url) || $this->url == '') $this->url = '/' . $this->name;
    if (!isset($this->displayName) || $this->url == '') $this->displayName = ucfirst($this->name);
    if (!isset($this->psName) || $this->psName == '') $this->psName = $this->name;
  }

  private function hydrate(array $data)
  {
    if (isset($data[0]) && !isset($data['name'])) $data['name'] = $data[0];
    foreach ($data as $property => $value) {
      if (is_string($value)) $value = trim($value);
      $this->__set($property, $value);
    }
  }

  function __get($property)
  {
    return (isset($this->{$property}) ? $this->{$property} : null);
  }

  function __set($property, $value)
  {
    if (property_exists($this, $property)) $this->{$property} = $value;
  }
}
