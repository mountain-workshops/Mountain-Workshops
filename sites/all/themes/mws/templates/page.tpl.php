<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 */

?>

  <div id="page">
    <div id="page-inner">
    <div id="header">
      <div class="section clearfix">
        <?php if ($logo): ?>
          <div id="logo"><?php print render($page['logo']) ?></div>
        <?php endif; ?>
          
        <?php if ($site_name): ?>
          <div id="name"><?php print render($page['site_name']); ?></div>
        <?php endif; ?>

        <?php if ($site_slogan): ?>
          <div id="site-slogan"><?php print $site_slogan; ?></div>
        <?php endif; ?>
        
        <?php if ($main_menu): ?>
          <div id="navigation"><?php print render($page['main_menu']); ?></div>
        <?php endif; ?>

        <?php print render($page['header']); ?>
      </div>
    </div>



    <?php print $messages; ?>

    <div id="main-wrapper">
      <div id="main" class="clearfix">
        <?php if ($page['sidebar_first']): ?>
          <div id="sidebar-first" class="column sidebar">
            <div class="section">
              <?php print render($page['sidebar_first']); ?>
            </div>
          </div>
        <?php endif; ?>

        <div id="content" class="column">
          <div class="section">
            
            <a id="main-content"></a>

            <?php if ($title): ?>
              <h1 class="title" id="page-title"><?php print $title; ?></h1>
            <?php endif; ?>

            <?php if ($tabs): ?>
              <div class="tabs">
                <?php print render($tabs); ?>
              </div>
            <?php endif; ?>

            <?php print render($page['help']); ?>

            <?php if ($action_links): ?>
              <ul class="action-links">
                <?php print render($action_links); ?>
              </ul>
            <?php endif; ?>

            <?php print render($page['content']); ?>
            <?php print $feed_icons; ?>
          </div>
        </div>

        <?php if ($page['sidebar_second']): ?>
          <div id="sidebar-second" class="column sidebar">
            <div class="section">
              <?php print render($page['sidebar_second']); ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div id="footer">
      <div class="section">
        <?php print render($page['footer']); ?>
      </div>
    </div>
  </div>
  </div>

