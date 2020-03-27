<?php
include('..inc/config.php');
include('..inc/panel.header.php');
include('..inc/panel.menu.php');
?>

<!--SERVICE CONTROL CENTER-->
<div class="panel panel-inverse">
  <div class="panel-heading">
    <h4 class="panel-title"><?php echo T('SERVICE_CONTROL_CENTER'); ?></h4>
  </div>
  <div class="panel-body" style="padding: 0">
    <div class="table-responsive">
      <table class="table table-hover nomargin" style="font-size:14px">
        <thead>
          <tr>
            <th class="text-center"><?php echo T('SERVICE_STATUS'); ?></th>
            <th class="text-center"><?php echo T('RESTART_SERVICES'); ?></th>
            <th class="text-center"><?php echo T('ENABLE_DISABLE_SERVICES'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if (file_exists("/install/.rtorrent.lock")) { ?>
            <tr>
              <?php
              $rtorrentrc = '/home/' . $username . '/.rtorrent.rc';
              if (file_exists($rtorrentrc)) {
                $rtorrentrc_data = file_get_contents($rtorrentrc);
                $scgiport = search($rtorrentrc_data, 'localhost:', "\n");
              }
              ?>
              <td><span id="appstat_rtorrent"></span> RTorrent <span class="tooltips" data-toggle="tooltip" title="scgi_port: <?php echo $scgiport; ?>" data-placement="right"><i class="tooltips fa fa-usb"></i><span></td>
              <td class="text-center"><a href="javascript:;" onclick="location.href='?service=rtorrent&action=restart'" class="btn btn-xs btn-default"><i class="fa fa-refresh text-info"></i> <?php echo T('REFRESH'); ?></a></td>
              <td class="text-center"><?php echo $services['rtorrent']->getHtmlSwitch(); ?></td>
            </tr>
          <?php } ?>

          <?php if (file_exists("/install/.deluge.lock")) { ?>
            <tr>
              <td><span id="appstat_deluged"></span> DelugeD </td>
              <td class="text-center"><a href="javascript:;" onclick="location.href='?service=deluged&action=restart'" class="btn btn-xs btn-default"><i class="fa fa-refresh text-info"></i> <?php echo T('REFRESH'); ?></a></td>
              <td class="text-center"><?php echo $services['deluged']->getHtmlSwitch(); ?></td>
            </tr>
            <tr>
              <td><span id="appstat_delugeweb"></span> Deluge Web </td>
              <td class="text-center"><a href="javascript:;" onclick="location.href='?service=deluge-web&action=restart'" class="btn btn-xs btn-default"><i class="fa fa-refresh text-info"></i> <?php echo T('REFRESH'); ?></a></td>
              <td class="text-center"><?php echo $service['deluged-web']->getHtmlSwitch(); ?></td>
            </tr>
          <?php } ?>

          <?php
          foreach ($services as $name => $service) :
            if ($service->process && file_exists('/install/.' . $name . '.lock') && $name != 'rtorrent') : ?>
              <tr>
                <td><span id="appstat_<?php echo $name ?>"></span> <?php echo $service->displayName ?> </td>
                <td class="text-center"><a href="javascript:;" onclick="location.href='?service=<?php echo $name ?>&action=restart'" class="btn btn-xs btn-default"><i class="fa fa-refresh text-info"></i> <?php echo T('REFRESH'); ?></a></td>
                <td class="text-center"><?php echo $service->getHtmlSwitch(); ?></td>
              </tr>
          <?php
            endif;
          endforeach; ?>
        </tbody>
      </table>
    </div><!-- table-responsive -->
  </div>
</div><!-- panel -->