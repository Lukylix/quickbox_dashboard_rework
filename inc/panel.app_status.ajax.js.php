<?php
function getValidJsFunctionName(string $name)
{
  return preg_replace('/[^a-zA-Z0-9]+/', '', $name);
}
?>

<script type="text/javascript">
  $(document).ready(function() {
    /////////////////////////////////////////////
    // BEGIN AJAX APP CALLS ON SERVICE STATUS //
    ///////////////////////////////////////////
    <?php foreach ($services as $name => $service) :
      if ($service->process) :
        $validJsfncName = getValidJsFunctionName($name); ?>

        function appstat_<?php echo $validJsfncName ?>() {
          $.ajax({
            url: "widgets/app_status.php?name=<?php echo $name ?>",
            cache: false,
            success: function(result) {
              $('#appstat_<?php echo $name ?>').html(result);
              setTimeout(function() {
                appstat_<?php echo $validJsfncName ?>()
              }, 5000);
            }
          });
        }
        appstat_<?php echo $validJsfncName ?>();
    <?php
      endif;
    endforeach; ?>
  });
</script>