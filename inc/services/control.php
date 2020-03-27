<?php

include_once('/srv/panel/inc/services/config.php');


if (isset($_GET['service']) && isset($_GET['action'])) {
  $service = getService($_GET['service']);
  $action = $_GET['action'];
  $isAdmin = (USERNAME == MASTERUSER) ? true : false;

  if ($service !== false && ($isAdmin || $service->visibleForAll)) {
    serviceControl($service->serviceName, $action, $service->rootService);
  }
}

function serviceControl($service, string $action, bool $rootService)
{
  switch ($action) {
    case "start":
    case "restart":
      shell_exec("sudo systemctl enable $service" . ($rootService ? '' : '@' . USERNAME));
      shell_exec("sudo systemctl $action $service" . ($rootService ? '' : '@' . USERNAME));
      break;
    case "stop":
      shell_exec("sudo systemctl stop $service" . ($rootService ? '' : '@' . USERNAME));
      shell_exec("sudo systemctl disable $service" . ($rootService ? '' : '@' . USERNAME));
      break;
  }
  header('Location: https://' . $_SERVER['HTTP_HOST'] . '/');
  exit();
}
