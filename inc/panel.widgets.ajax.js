$(document).ready(function() {

  function uptime() {
    $.ajax({url: "widgets/up.php", cache:true, success: function (result) {
      $('#uptime').html(result);
      setTimeout(function(){uptime()}, 1000);
    }});
  }
  uptime();

  function sload() {
    $.ajax({url: "widgets/load.php", cache:true, success: function (result) {
      $('#cpuload').html(result);
      setTimeout(function(){sload()}, 15000);
    }});
  }
  sload();

  function bwtables() {
    $.ajax({url: "widgets/bw_tables.php", cache:false, success: function (result) {
      $('#bw_tables').html(result);
      setTimeout(function(){bwtables()}, 60000);
    }});
  }
  bwtables();

  function diskstats() {
    $.ajax({url: "widgets/disk_data.php", cache:false, success: function (result) {
      $('#disk_data').html(result);
      setTimeout(function(){diskstats()}, 15000);
    }});
  }
  diskstats();

  function ramstats() {
    $.ajax({url: "widgets/ram_stats.php", cache:false, success: function (result) {
      $('#meterram').html(result);
      setTimeout(function(){ramstats()}, 15000);
    }});
  }
  ramstats();

  function activefeed() {
    $.ajax({url: "widgets/activity_feed.php", cache:false, success: function (result) {
      $('#activityfeed').html(result);
      setTimeout(function(){activefeed()}, 300000);
    }});
  }
  activefeed();

  function msgoutput() {
    $.ajax({url: "db/output.log", cache:false, success: function (result) {
      $('#sshoutput').html(result);
      setTimeout(function(){msgoutput()}, 1000);
    }});
    jQuery( function(){
      var pre = jQuery("#sysPre");
      pre.scrollTop( pre.prop("scrollHeight") );
    });
  }
  msgoutput();

  });
