<div class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php if (!$label_hidden): ?>
    <div class="field-label"<?php print $title_attributes; ?>><?php print $label ?>:&nbsp;</div>
  <?php endif; ?>
  <div class="field-items"<?php print $content_attributes; ?>>
    <?php foreach ($items as $delta => $item): ?>
      <div class="field-item <?php print $delta % 2 ? 'odd' : 'even'; ?>"<?php print $item_attributes[$delta]; ?>>
      
      <?php 
        $title = $item['#item']['title']; 
        $alt = $item['#item']['alt'];
        $item['#item']['alt'] = $title;
        $item['#item']['title'] = $title . ' Photo by ' . $alt . '.';
      ?>
      
        <?php print render($item); ?>
        <div class="byline"><?php print $alt; ?></div>
        <div class="caption"><?php print $title; ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>