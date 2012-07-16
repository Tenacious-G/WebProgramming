<feed xmlns="http://www.w3.org/2005/Atom">
  <id><?php echo Router::url('/',true); ?></id>
  <title><?php echo Configure::read('appSettings.name'); ?></title>
  <updated><?php echo $time->toAtom($nodes[0]['Node']['modified']);?></updated>
  <link type="application/atom+xml" rel="self" href="<?php echo Router::url('/',true); ?>feeds/<?php echo $feed; ?>.xml"/>
  <link type="text/html" rel="alternate" href="<?php echo Router::url('/',true); ?>"/>
  <author>
    <name><?php echo Configure::read('appSettings.name'); ?></name>
  </author>
  <?php foreach ($nodes as $node):?>
  <entry>
    <title>
    <![CDATA[
      <?php echo h($node['Node']['name']); ?>
    ]]>
    </title>
    <link href="<?php echo Router::url(array('controller'=>'nodes', 'action'=>'view', $node['City']['slug'], $node['Node']['slug']),true); ?>" />
    <id>tag:<?php echo $_SERVER['SERVER_NAME']; ?>,<?php echo $time->format('Y-m-d',$node['Node']['created']); ?>:<?php echo 
	Router::url(array('controller'=>'nodes', 'action'=>'view', $node['City']['slug'], $node['Node']['slug'])); ?></id>
    <published><?php echo $time->toAtom($node['Node']['created']); ?></published>
    <updated><?php echo $time->toAtom($node['Node']['modified']); ?></updated>
  <author>
    <name><?php echo Configure::read('appSettings.name'); ?></name>
  </author>
  <content type="html">
      <![CDATA[
      <?php  echo nl2br(h($node['Node']['notes'])); ?>
      ]]>
  </content>
  </entry>
  <?php endforeach; ?>
      
</feed>