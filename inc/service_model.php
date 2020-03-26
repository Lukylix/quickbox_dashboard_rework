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
    if ($this->process) $this->processExists();
  }

  private function processExists()
  {
    if (isset($this->psUser)) {
      if (stripos(USER_RUNNING, $this->psName) !== false) $this->exist = true;
    } else {
      exec("ps -fu $this->psUser | grep -iE $this->psName | grep -v grep", $pids);
      if (count($pids) > 0) $this->exist = true;
    }
  }

  private function
  default()
  {
    if (!isset($this->url) or $this->url == '') $this->url = '/' . $this->name;
    if (!isset($this->displayName) or $this->url == '') $this->displayName = ucfirst($this->name);
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
