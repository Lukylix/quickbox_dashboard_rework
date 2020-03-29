</section>
<!-- THEME SELECT MODAL -->
<?php $option = array();
              $option[] = array('file' => 'defaulted', 'title' =>'Defaulted');
              $option[] = array('file' => 'smoked', 'title' =>'Smoked'); { ?>
<?php foreach($option as $theme) { ?>
<div class="modal bounceIn animated" id="themeSelect<?php echo $theme['file'] ?>Confirm" tabindex="-1" role="dialog" aria-labelledby="ThemeSelect<?php echo $theme['file'] ?>Confirm" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="ThemeSelect<?php echo $theme['file'] ?>Confirm"><?php echo $theme['title'] ?></h4>
      </div>
      <div class="modal-body">
        <?php echo T('THEME_CHANGE_TXT'); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo T('CANCEL'); ?></button>
        <a href="?themeSelect-<?php echo $theme['file'] ?>=true" id="themeSelect<?php echo $theme['file'] ?>Go" class="btn btn-primary"><?php echo T('AGREE'); ?></a>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modal -->
<?php } ?>
<?php } ?>
<!-- SYSTEM RESPONSE MODAL -->
<div class="modal bounceIn animated" id="sysResponse" tabindex="-1" role="dialog" aria-labelledby="sysResponse" aria-hidden="true">
  <div class="modal-dialog" style="width: 600px">
    <div class="modal-content" style="background:rgba(0, 0, 0, 0.6);border:2px solid rgba(0, 0, 0, 0.2)">
      <div class="modal-header" style="background:rgba(0, 0, 0, 0.4);border:0!important">
        <h4 class="modal-title" style="color:#fff"><?php echo T('SYSTEM_RESPONSE_TITLE'); ?></h4>
      </div>
      <div class="modal-body ps-container" style="background:rgba(0, 0, 0, 0.4); max-height:600px;" id="sysPre">
        <pre style="color: rgb(83, 223, 131) !important;" class="sysout ps-child"><span id="sshoutput"></span></pre>
      </div>
      <div class="modal-footer" style="background:rgba(0, 0, 0, 0.4);border:0!important">
        <a href="?clean_log=true" class="btn btn-xs btn-danger"><?php echo T('CLOSE_REFRESH'); ?></a>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modal -->

<!--script src="js/script.js"></script-->
<script src="lib/jquery-ui/jquery-ui.js"></script>
<script src="lib/jquery.ui.touch-punch.min.js"></script>
<script src="lib/bootstrap/js/bootstrap.js"></script>
<script src="lib/jquery-toggles/toggles.js"></script>
<script src="lib/jquery-knob/jquery.knob.js"></script>
<script src="lib/jquery.gritter/jquery.gritter.js"></script>
<script src="js/quick.js"></script>


<script>
  $(function(){
    $('#rutorrent').on('loaded.lobiPanel', function (ev, lobiPanel) {
      var $body = lobiPanel.$el.find('.panel-body');
      $body.html('<div>' + $body.html() + '</div>');
  });
});
</script>

<script src="js/jquery.scrollbar.js"></script>
<script>
$(function() {
  $('.leftpanel').perfectScrollbar();
  $('.leftpanel').perfectScrollbar({ wheelSpeed: 1, wheelPropagation: true, minScrollbarLength: 20 });
  $('.leftpanel').perfectScrollbar('update');
  //$('.body').perfectScrollbar();
  //$('.body').perfectScrollbar({ wheelSpeed: 1, wheelPropagation: true, swipePropagation: true, minScrollbarLength: 20 });
  //$('.body').perfectScrollbar('update');
  $('.modal-body').perfectScrollbar();
  $('.modal-body').perfectScrollbar({ wheelSpeed: 1, wheelPropagation: true, minScrollbarLength: 20 });
  $('.modal-body').perfectScrollbar('update');
  $('.sysout').perfectScrollbar();
  $('.sysout').perfectScrollbar({ wheelSpeed: 1, wheelPropagation: true, minScrollbarLength: 20 });
  $('.sysout').perfectScrollbar('update');
});
</script>
<script>
$(function() {
  // Toggles
  $('.toggle-en').toggles({
    on: true,
    height: 26,
    width: 100,
    text: {
      on: "<?php echo T('ENABLED') ?>"
    }
  });
  $('.toggle-dis').toggles({
    on: false,
    height: 26,
    width: 100,
    text: {
      off: "<?php echo T('DISABLED') ?>"
    }
  });
});
</script>

<script>
$(document).ready(function() {
  $('#sysResponse').on('hidden.bs.modal', function () {
    location.reload();
  });
});
</script>

<script src="lib/datatables/jquery.dataTables.js"></script>
<script src="lib/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="lib/select2/select2.js"></script>

<script>
$(document).ready(function() {

  'use strict';

  $('#dataTable1').DataTable();

  var exRowTable = $('#exRowTable').DataTable({
    responsive: true,
    'fnDrawCallback': function(oSettings) {
      $('#exRowTable_paginate ul').addClass('pagination-active-success');
    },
    'ajax': 'ajax/objects.txt',
    'columns': [{
      'class': 'details-control',
      'orderable': false,
      'data': null,
      'defaultContent': ''
    },
    { 'data': 'name' },
    { 'data': 'details' },
    { 'data': 'availability' }
    ],
    'order': [[1, 'asc']]
  });

  // Add event listener for opening and closing details
  $('#exRowTable tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = exRowTable.row( tr );

    if ( row.child.isShown() ) {
      // This row is already open - close it
      row.child.hide();
      tr.removeClass('shown');
    } else {
      // Open this row
      row.child( format(row.data()) ).show();
      tr.addClass('shown');
    }
  });

  function format (d) {
    // `d` is the original data object for the row
    return '<h4>'+d.name+'<small>'+d.details+'</small></h4>'+
    '<p class="nomargin">Nothing to see here.</p>';
  }

  // Select2
  $('select').select2({ minimumResultsForSearch: Infinity });

});
</script>
