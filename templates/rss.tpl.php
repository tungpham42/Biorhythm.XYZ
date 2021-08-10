<div class="card-columns">
<?php
// We'll process this feed with all of the default options.
$feed = new SimplePie();
// Set the feed to process.
$feed->set_feed_url('https://biorhythm.xyz/blog/feed/');
// Run SimplePie.
$feed->init();
// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$feed->handle_content_type();
if ($feed->error): ?> 
    <p><?php echo $feed->error; ?></p>   
<?php
endif;
/*
Here, we'll loop through all of the items in the feed, and $item represents the current item in the loop.
*/
foreach ($feed->get_items() as $item):
?>
  <div class="card rounded">
    <div class="card-body">
      <h5 class="card-title"><a target="_blank" href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h5>
      <p class="card-text"><?php echo strip_tags($item->get_description()); ?></p>
      <p class="card-text"><small class="text-muted"><?php echo $item->get_date('Y-m-d | g:i a'); ?></small></p>
    </div>
  </div>
<?php endforeach; ?>
</div>