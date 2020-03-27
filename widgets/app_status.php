<?php

include '/srv/panel/inc/services/config.php';


if (isset($_GET['name'])) {
  $service = getService($_GET['name']);
  if ($service !== false && $service->exist) {
    echo "<span class=\"badge badge-service-running-dot\"></span><span class=\"badge badge-service-running-pulse\"></span>";
  } else {
    echo "<span class=\"badge badge-service-disabled-dot\"></span><span class=\"badge badge-service-disabled-pulse\"></span>";
  }
}
