<script type="text/javascript">
  $(document).ready(function() {
    /////////////////////////////////////////////
    // BEGIN AJAX APP CALLS ON SERVICE STATUS //
    ///////////////////////////////////////////
    function appstats() {
      <?php
      foreach ($services as $name => $service) :
        if ($service->process) : ?>
          if ($('#appstat_<?php echo $name ?>').length > 0) {
            $.ajax({
              url: "widgets/app_status.php?name=<?php echo $name ?>",
              cache: false,
              success: function(result) {
                $('#appstat_<?php echo $name ?>').html(result);
              }
            });
          }
      <?php
        endif;
      endforeach; ?>
      setTimeout(function() {
        appstats()
      }, 5000);
    }
    appstats();
  });
</script>