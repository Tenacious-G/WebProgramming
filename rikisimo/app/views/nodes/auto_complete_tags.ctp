<ul class="autocomplete">
 <?php foreach($tags as $tag): ?>
     <li><?php echo h($tag['Tag']['name']); ?></li>
 <?php endforeach; ?>
</ul> 