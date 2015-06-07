<div class='main-box'>
	<h1><?= $title ?></h1>
	<ul>
		<?php foreach ( $items as $item ) : ?>

		<?php $user = $this->users->findUserById($item->user_id); ?>

		<li>
			<a href='<?= $this->url->create('users/id/' . $user[0]->id ) ?>'>
				<?= $user[0]->acronym ?>
			</a> 
			<span class='pull-right'>has created <?= $item->no ?></span>
		</li>

		<?php endforeach; ?>
	</ul>
</div>