<div class='main-box'>
	<h1><?= $title ?></h1>
	<ul>
		<?php foreach ( $items as $item => $value ) : ?>
		
		<li class='tag'>
			<a class='post-tag post-tag-<?= $item ?>' href='<?= $this->url->create('questions/tag/' . $item) ?>'><?= $item ?></a>
			<span class='pull-right'><?= $value ?> posts</span>
		</li>

		<?php endforeach; ?>
	</ul>
</div>