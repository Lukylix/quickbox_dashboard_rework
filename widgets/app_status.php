<?php

include '/srv/panel/inc/services/config.php';

/**
 * Search the service form the config and return the service object
 */
function searchService($servicesConfig, $name)
{
  foreach ($servicesConfig as $service) {
    if ((isset($service[0]) && $service[0] == $name) || (isset($service['name']) && $service['name'] == $name))
      return new Service($service);
  }
}

if (isset($_GET['name'])) {
  $service = searchService($servicesConfig, $_GET['name']);
  if ($service->exist) {
    echo "<span class=\"badge badge-service-running-dot\"></span><span class=\"badge badge-service-running-pulse\"></span>";
  } else {
    echo "<span class=\"badge badge-service-disabled-dot\"></span><span class=\"badge badge-service-disabled-pulse\"></span>";
  }
}
