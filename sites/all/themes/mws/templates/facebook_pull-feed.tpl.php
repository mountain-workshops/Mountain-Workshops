<?php $item_count = count($items); ?>
<?php $item_id = 1; ?>

<ul class="facebook-feed">
<?php foreach ($items as $item_key => $item): ?>
  <?php if (isset($item->message)): ?>
    <li class="item feed-item-<?php echo $item_id; ?> <?php echo ($item_key == $item_count - 1) ? 'last' : ''; ?>">
      <span class="facebook-feed-picture"><img alt="<?php echo $item->from->name; ?>" src="http://graph.facebook.com/<?php echo $item->from->id; ?>/picture" /></span>
      <span class="facebook-feed-from"><a href="http://facebook.com/profile.php?id=<?php echo $item->from->id; ?>"><?php echo $item->from->name; ?></a></span>
      <span class="facebook-feed-message">
        <?php echo $item->message; ?>
        <?php if ($item->type === 'link'): ?>
          <?php echo l($item->name, $item->link); ?>
        <?php endif; ?>
      </span>
      <span class="facebook-feed-time"><?php echo t('!time ago.', array('!time' => format_interval(time() - strtotime($item->created_time)))); ?></span>
    </li>
    <?php $item_id ++; ?>
  <?php endif; ?>
<?php endforeach; ?>
</ul>
