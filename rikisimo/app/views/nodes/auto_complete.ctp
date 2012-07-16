<ul class="autocomplete">
 <?php foreach($cities as $city): ?>
     <li><?php echo h($city['City']['name']); ?></li>
 <?php endforeach; ?>
</ul> 