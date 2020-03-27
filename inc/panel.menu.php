<?php
$current_vs = "<span id=\"version-result\"></span>";
?>

<body class="body">
  <header>
    <div class="headerpanel">
      <div class="logopanel">
        <h2><?php include("db/branding-l.php"); ?></h2>
      </div><!-- logopanel -->
      <div class="headerbar">
        <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
        <div class="header-right">
          <ul class="headermenu">
            <?php if (file_exists('/install/.developer.lock')) { ?>
              <li>
                <div class="btn-group">
                  <button type="button" class="btn btn-logged">
                    <a href="#" class="label label-warning">You are on the QuickBox Development Repo</a>
                  </button>
                </div>
              </li>
            <?php } ?>
            <?php if (ISADMIN) { ?>
              <li>
                <div id="noticePanel" class="btn-group">
                  <button class="btn" data-toggle="dropdown">
                    <i class="fa fa-menu"></i> swizzin Menu <span class="caret"></span>
                  </button>
                  <div id="noticeDropdown" class="dropdown-menu dm-notice pull-right">
                    <div role="tabpanel">
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li class="active"><a data-target="#quickplus" data-toggle="tab">swizzin+</a></li>
                        <li><a data-target="#chat" data-toggle="tab">Chat</a></li>
                        <li><a data-target="#dashadjust" data-toggle="tab">Dashboard</a></li>
                      </ul>

                      <!-- Tab panes -->
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="quickplus">
                          <ul class="list-group">
                            <li class="list-group-item">
                              <div class="row">
                                <div class="col-xs-12">
                                  <small><a href="https://github.com/liaralabs/swizzin/blob/master/README.md" target="_blank">README</a></small>
                                  <small><a href="https://github.com/liaralabs/swizzin/blob/master/CHANGELOG.md" target="_blank">CHANGELOG</a></small>
                                </div>
                              </div>
                            </li>

                            <li class="list-group-item">
                              <div class="row">
                                <div class="col-xs-12">
                                  <h5>swizzin</h5>
                                </div>
                                <div class="col-xs-12">
                                  <div class="col-xs-12 col-md-6" style="padding: 0">
                                    <ul style="padding-left: 5px">
                                      <li><small><a href="https://github.com/liaralabs/swizzin" target="_blank" alt="View swizzin on github">Github</a></small></li>
                                      <li><small><a href="https://swizzin.ltd" target="_blank" alt="swizzin homepage">Home</a></small></li>
                                    </ul>
                                  </div>
                                  <div class="col-xs-12 col-md-6" style="padding: 0">
                                    <ul style="padding-left: 5px">
                                      <li><small><a href="https://github.com/liaralabs/swizzin/wiki" target="_blank" alt="swizzin wiki">Wiki</a></small></li>
                                      <li><small><a href="https://github.com/liaralabs/swizzin/issues" target="_blank"><?php echo T('ISSUE_REPORT_TXT'); ?></a></small></li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </li>
                          </ul>
                          <!--a class="btn-more" href="">View More QuickBox <i class="fa fa-long-arrow-right"></i></a-->
                        </div><!-- tab-pane -->

                        <div role="tabpanel" class="tab-pane" id="chat">
                          <ul class="list-group notice-list">
                            <li class="list-group-item" style="padding-top: 10px;">
                              <div class="row">
                                <div class="col-xs-2">
                                  <i class="fa fa-comment"></i>
                                </div>
                                <div class="col-xs-10">
                                  <h5><?php echo T('JOIN_US_TXT'); ?></h5>
                                  <small style="color:#fff"><strong style="color: #4CD4B0">host:</strong> irc.swizzin.ltd +6697</small>
                                  <small style="color:#fff"><strong style="color: #4CD4B0">chan:</strong> #swizzin</small>
                                </div>
                              </div>
                            </li>
                          </ul>
                        </div><!-- tab-pane -->

                        <div role="tabpanel" class="tab-pane" id="dashadjust">
                          <ul class="list-group">
                            <li class="list-group-item">
                              <div class="row">
                                <div class="col-xs-12">
                                  <div class="col-xs-12 col-md-6" style="padding: 0">
                                    <?php $language = array();
                                    $language[] = array('file' => 'lang_es', 'title' => 'Spanish');
                                    $language[] = array('file' => 'lang_dk', 'title' => 'Danish');
                                    $language[] = array('file' => 'lang_en', 'title' => 'English');
                                    $language[] = array('file' => 'lang_fr', 'title' => 'French');
                                    $language[] = array('file' => 'lang_de', 'title' => 'German'); { ?>
                                      <h5><?php echo T('LANG_SELECT'); ?></h5>
                                      <?php foreach ($language as $lang) { ?>
                                        <small><a href='?langSelect-<?php echo $lang['file'] ?>=true'><img class='lang-flag' src='lang/flag_<?php echo $lang['file'] ?>.png' /><?php echo $lang['title'] ?></a></small>
                                      <?php } ?>
                                    <?php } ?>
                                  </div>
                                  <div class="col-xs-12 col-md-6" style="padding: 0">
                                    <?php $option = array();
                                    $option[] = array('file' => 'defaulted', 'title' => 'Defaulted');
                                    $option[] = array('file' => 'smoked', 'title' => 'Smoked'); { ?>
                                      <h5><?php echo T('THEME_SELECT'); ?></h5>
                                      <?php foreach ($option as $theme) { ?>
                                        <small><a href="javascript:void()" data-toggle="modal" data-target="#themeSelect<?php echo $theme['file'] ?>Confirm"><img class='lang-flag' src='img/themes/opt_<?php echo $theme['file'] ?>.png' /><?php echo $theme['title'] ?></a></small>
                                      <?php } ?>
                                    <?php } ?>
                                  </div>
                                </div>
                              </div>
                            </li>
                          </ul>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            <?php } ?>
            <li>
              <div class="btn-group">
                <button type="button" class="btn btn-logged" data-toggle="dropdown">
                  <?php echo USERNAME; ?>
                  <span class="caret"></span>
                </button>
                <?php include("db/branding-m.php"); ?>
              </div>
            </li>
          </ul>
        </div><!-- header-right -->
      </div><!-- headerbar -->
    </div><!-- header-->
  </header>
  <section>
    <div class="leftpanel ps-container">
      <div class="leftpanelinner">
        <?php if (file_exists('/install/.foo.lock')) { ?>
          <ul class="nav nav-tabs nav-justified nav-sidebar">
            <li class="tooltips active" data-toggle="tooltip" title="<?php echo T('MAIN_MENU'); ?>" data-placement="bottom"><a data-toggle="tab" data-target="#mainmenu"><i class="tooltips fa fa-ellipsis-h"></i></a></li>
          </ul>
        <?php } ?>
        <div class="tab-content">
          <!-- ################# MAIN MENU ################### -->
          <div class="tab-pane active" id="mainmenu">
            <h5 class="sidebar-title"><?php echo T('MAIN_MENU'); ?></h5>
            <ul class="nav nav-pills nav-stacked nav-quirk">
              <?php
              foreach ($services as $nom => $service) :
                if ($service->menu) :
                  if ($service->exist || (!$service->process && file_exists('/install/.' . $nom . '.lock'))) : ?>
                    <li><a href="<?php echo $service->url ?>" target="_blank"><img src="img/brands/<?php echo $nom . ($nom == 'lounge' ? '.svg' : '.png') ?>" class="brand-ico"> <span><?php echo $service->displayName ?></span></a></li>
              <?php
                  endif;
                endif;
              endforeach; ?>
              <?php if (file_exists('/install/.rtorrent.lock') || file_exists('/install/.deluge.lock') || file_exists('/install/.flood.lock')) { ?>
                <li class="nav-parent">
                  <a href=""><i class="fa fa-download"></i> <span><?php echo T('DOWNLOADS'); ?></span></a>
                  <ul class="children">
                    <?php if (file_exists('/install/.rutorrent.lock') || file_exists('/install/.flood.lock')) { ?>
                      <li><a href="/rtorrent.downloads" target="_blank">rTorrent</a></a></li>
                    <?php } ?>
                    <?php if (file_exists('/install/.deluge.lock')) { ?>
                      <li><a href="/deluge.downloads" target="_blank">Deluge</a></li>
                    <?php } ?>
                    <?php if (file_exists('/home/' . USERNAME . '/public_html/' . USERNAME . '.zip')) { ?>
                      <li><a href="/~<?php echo USERNAME ?>/<?php echo USERNAME ?>.zip" target="_blank"> <span>OpenVPN Config</span></a></li>
                    <?php } ?>
                  </ul>
                </li>
              <?php } ?>
              <?php if ($services['shellinabox']->exist && ISADMIN) { ?>
                <li><a href="/shell" target="_blank"><i class="fa fa-keyboard-o"></i> <span><?php echo T('WEB_CONSOLE'); ?></span></a></li>
              <?php } ?>
            </ul>
          </div><!-- tab pane -->
        </div><!-- tab-content -->
      </div><!-- leftpanelinner -->
    </div><!-- leftpanel -->