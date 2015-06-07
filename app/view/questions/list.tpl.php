<div class='main-box'>
	<h1><?= $title ?></h1>
	<ul>
		<?php foreach ( $items as $item ) : ?>
		
		<li><a href='<?= $this->url->create('questions/view/' . $item->id . '/rating') ?>'><?= $item->title ?></a></li>

		<?php endforeach; ?>
	</ul>
</div>