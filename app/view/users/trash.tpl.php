<h1 class='content-subhead'><?= $title ?></h1>

<?php if ( !empty($users) ) : ?>

<table class='pure-table pure-table-horizontal'>
	<thead>
		<tr>
			<th>#</th>
			<th>Acronym</th>
			<th>Email</th>
			<th></th>
		</tr>
	</thead>
	<tbody>

<?php foreach ( $users as $user ) : ?>

	<tr>
		<td><?= $user->id ?></td>
		<td><a href='<?= $this->url->create('users/id/' . $user->id) ?>'><span class='fa fa-user'></span> <?= $user->acronym ?></a></td>
		<td><?= $user->email ?></td>
		<td><a href='<?= $this->url->create('users/regret/' . $user->id) ?>'>Throw back</a></td>
	</tr>

<?php endforeach; ?>

	</tbody>
</table>

<p><a href='<?= $this->url->create('users/empty') ?>' role='button' class='pure-button-primary pure-button'>Empty garbage bin</a></p>

<?php endif; ?>