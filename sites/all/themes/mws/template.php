<?php


/**
 * Implements hook_preprocess().
 */
function mws_preprocess(&$variables) {
  // Consolidate attributes by moving classes into attributes_array.
  $variables['attributes_array']['class'] = $variables['classes_array'];
}

/**
 * Implements hook_preprocess_html().
 */
function mws_preprocess_html(&$variables) {
  $active_themes = _mws_theme_collector();
  _mws_process_stylesheets($active_themes);

  $variables['skip_link'] = array(
    '#theme' => 'link',
    '#prefix' => '<div id="skip-link">',
    '#suffix' => '</div>',
    '#href' => '',
    '#text' => t('Skip to main content'),
    '#options' => array(
      'html' => FALSE,
      'fragment' => 'main-content',
      'attributes' => array(
        'class' => array(
          'element-invisible',
          'element-focusable',
        ),
      ),
    ),
  );
}


function mws_links__system_main_menu($variables){
	
  $links = $variables['links'];
  $attributes = $variables['attributes'];
  $heading = $variables['heading'];
  global $language_url;
  $output = '';

  if (count($links) > 0) {
    $output = '';

    // Treat the heading first if it is present to prepend it to the
    // list of links.
    if (!empty($heading)) {
      if (is_string($heading)) {
        // Prepare the array that will be used when the passed heading
        // is a string.
        $heading = array(
          'text' => $heading,
          // Set the default level of the heading. 
          'level' => 'h2',
        );
      }
      $output .= '<' . $heading['level'];
      if (!empty($heading['class'])) {
        $output .= drupal_attributes(array('class' => $heading['class']));
      }
      $output .= '>' . check_plain($heading['text']) . '</' . $heading['level'] . '>';
    }

    $output .= '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = array($key);

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class[] = 'first';
      }
      if ($i == $num_links) {
        $class[] = 'last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
           && (empty($link['language']) || $link['language']->language == $language_url->language)) {
        $class[] = 'active';
      }
      $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
		
		if (isset($link['attributes'])) {
			if(isset($link['attributes']['title'])){
				$output .= '<span>' . $link['attributes']['title']  . '</span>';
			}
		}
      }
      elseif (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}


/**
 * Implements hook_preprocess_page().
 */
function mws_preprocess_page(&$variables) {
  // Make the site name into a renderable array.
  // Use a h1 tag for the site name when the content title is empty.
  $tag = (!empty($variables['title'])) ? 'div' : 'h1';
  $variables['page']['site_name'] = array(
    '#theme' => 'link',
    '#href' => $variables['front_page'],
    '#options' => array(
      'attributes' => array(
        'title' => t('Home'),
        'rel' => 'home',
      ),
      'html' => TRUE,
    ),
    '#text' => '<span>' . $variables['site_name'] . '</span>',
    '#prefix' => '<' . $tag . ' id="site-name">',
    '#suffix' => '</' . $tag . '>',
  );

  // Make the logo into a renderable array.
  $logo_image = array(
    '#theme' => 'image',
    '#path' => $variables['logo'],
    '#alt' => t('Home'),
  );
  $variables['page']['logo'] = array(
    '#theme' => 'link',
    '#href' => $variables['front_page'],
    '#options' => array(
      'attributes' => array(
        'title' => t('Home'),
        'rel' => 'home',
        'id' => 'logo',
      ),
      'html' => TRUE,
    ),
    '#text' => render($logo_image),
  );

//  foreach($variables['main_menu'] as $menu_item){
//    if (isset($menu_item['attributes'])) {
//      $menu_item['title'] = $menu_item['title'] . '<span>' . $menu_item['attributes']['title'] . '</span>';
//    }
//    $variables['new_main_menu'][] = $menu_item; 
//  }

  // Make main and secondary menus into renderable arrays.
  $variables['page']['main_menu'] = array(
    '#theme' => 'links__system_main_menu',
    '#links' => $variables['main_menu'],
    '#attributes' => array(
      'id' => 'main-menu',
      'class' => array(
        'links',
        'inline',
        'clearfix',
      ),
    ),
    '#heading' => t(''),
  );
  $variables['page']['secondary_menu'] = array(
    '#theme' => 'links__system_secondary_menu',
    '#links' => $variables['secondary_menu'],
    '#attributes' => array(
      'id' => 'secondary-menu',
      'class' => array(
        'links',
        'inline',
        'clearfix',
      ),
    ),
    '#heading' => t(''),
  );
}

/**
 * Implements hook_preprocess_node().
 */
function mws_preprocess_node(&$variables) {
  global $theme_key;

  $node = $variables['node'];

  // Make the title into a link and keep it as a renderable array.
  if (!empty($variables['title'])) {
    $variables['title_link'] = array(
      '#theme' => 'link',
      '#path' => 'node/' . $node->nid,
      '#text' => $variables['title'],
      '#options' => array(
        'html' => FALSE,
        'attributes' => array(),
      ),
    );
  }

  // Add some additional node classes.
  mws_node_classes($variables);

  // Call THEME_preprocess_node_TYPE functions if any exist.
  $function = $theme_key . '_preprocess_node_' . $node->type;
  if (function_exists($function)) {
    call_user_func_array($function, array(&$variables));
  }
}

/**
 * Implements hook_preprocess_comment().
 */
function mws_preprocess_comment(&$variables) {
  $comment = $variables['comment'];

  $variables['attributes_array']['id'] = 'comment-' . $comment->cid;
  $variables['attributes_array']['class'][] = $variables['status'];
  
  if ($comment->new) {
    $variables['attributes_array']['class'][] = 'comment-new';
  }
  
  if ($variables['zebra']) {
    $variables['attributes_array']['class'][] = 'comment-' . $variables['zebra'];
  }
}

/**
 * Implements hook_preprocess_block().
 */
function mws_preprocess_block(&$variables) {
  $block = $variables['block'];
  
  $variables['attributes_array']['id'] = $variables['block_html_id'];
  $variables['title_attributes_array']['class'][] = 'block-title';
  $variables['content_attributes_array']['class'][] = 'block-content';
  $variables['content_attributes_array']['class'][] = 'clearfix';

  $variables['title'] = $block->subject;
}


/**
 * Generate additional classes for nodes.
 *
 * @see mws_preprocess_node()
 */
function mws_node_classes(&$variables) {
  $node = $variables['node'];

  // Generate classes for the node container.
  $classes = &$variables['attributes_array']['class'];
  $classes[] = 'node-type-' . $node->type;
  if ($variables['page']) {
    $classes[] = 'node-page' . $node->type;
  }
  elseif ($variables['teaser']) {
    $classes[] = 'node-teaser-' . $node->type;
  }
  if ($variables['sticky']) {
    $classes[] = 'sticky';
  }
  if (!$variables['status']) {
    $classes[] = 'node-unpublished';
  }

  // Generate classes for the content container.
  $variables['content_attributes_array']['class'][] = 'node-content';
  $variables['content_attributes_array']['class'][] = 'clearfix';

  $variables['attributes_array']['id'] = 'node-' . $node->nid;
}

/**
 * Check for stylesheets to be placed at the top of the stack or conditional
 * Internet Explorer styles in the .info file and add them to the $styles
 * variable.
 */
function _mws_process_stylesheets($active_themes) {
  // Prepare the needed variables.
  global $theme_info;
  $framework_styles = array();
  $conditional_styles = array();

  // If there is more than one active theme, check all base themes for
  // stylesheets.
  if (count($active_themes) > 1) {
    global $base_theme_info;
    foreach ($base_theme_info as $name => $info) {
      if (isset($info->info['framework stylesheets'])) {
        $framework_styles[$name] = $info->info['framework stylesheets'];
      }
      if (isset($info->info['conditional stylesheets'])) {
        $conditional_styles[$name] = $info->info['conditional stylesheets'];
      }
    }
  }

  // Check the current theme for stylesheets.
  if (isset($theme_info->info['framework stylesheets'])) {
    $framework_styles[$theme_info->name] = $theme_info->info['framework stylesheets'];
  }
  if (isset($theme_info->info['conditional stylesheets'])) {
    $conditional_styles[$theme_info->name] = $theme_info->info['conditional stylesheets'];
  }

  // If there is at least one entry in the $framework_styles array, process it.
  if (count($framework_styles) >= 1) {
    // Add all the framework stylesheets to a group so they are loaded first.
    foreach ($framework_styles as $theme => $medias) {
      foreach ($medias as $media => $stylesheets) {
        foreach ($stylesheets as $path) {
          $path = drupal_get_path('theme', $theme) . '/' . $path;
          drupal_add_css($path, array(
            'group' => CSS_SYSTEM,
            'media' => $media,
            'weight' => -1000,
            'every_page' => TRUE,
          ));
        }
      }
    }
  }

  // If there is at least one entry in the $conditional_styles array, process it.
  if (count($conditional_styles) >= 1) {
    // Add all the conditional stylesheets with drupal_add_css().
    foreach ($conditional_styles as $theme => $conditions) {
      foreach ($conditions as $condition => $medias) {
        foreach ($medias as $media => $stylesheets) {
          foreach ($stylesheets as $path) {
            $path = drupal_get_path('theme', $theme) . '/' . $path;
            if ($condition == '!ie') {
              $browsers = array('!IE' => TRUE, 'IE' => FALSE);
            }
            else {
              $browsers = array('!IE' => FALSE, 'IE' => $condition);
            }
            drupal_add_css($path, array(
              'media' => $media,
              'every_page' => TRUE,
              'browsers' => $browsers,
            ));
          }
        }
      }
    }
  }
}

/**
 * Collect all information for the active theme.
 */
function _mws_theme_collector() {
  $themes = list_themes();
  $active_themes = array();
  global $theme_info;

  // If there is a base theme, collect the names of all themes that may have
  // data files to load.
  if (isset($theme_info->base_theme)) {
    global $base_theme_info;
    foreach ($base_theme_info as $base){
      $active_themes[$base->name] = $themes[$base->name];
      $active_themes[$base->name]->path = drupal_get_path('theme', $base->name);
    }
  }

  // Add the active theme to the list of themes that may have data files.
  $active_themes[$theme_info->name] = $themes[$theme_info->name];
  $active_themes[$theme_info->name]->path = drupal_get_path('theme', $theme_info->name);

  return $active_themes;
}

