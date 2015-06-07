<?php if ( !empty($users) ) : ?>

<h1 class='content-subhead'><?= $title ?></h1>

<section class='tags'>

<?php foreach ( $users as $user ) : ?>

<div class='user-box'>
	<p>
		<img class='pure-img' alt='<?= $user->acronym ?>' src='http://www.gravatar.com/avatar/<?= md5(strtolower(trim($user->email))) ?>s=40'>
		<span class='fa fa-user'></span>
		<a href='<?= $this->url->create('users/id/' . $user->id) ?>'><?= $user->acronym ?></a>
	</p>
	<p></p>
</div>

<?php endforeach; ?>

</section>

<?php endif; ?>