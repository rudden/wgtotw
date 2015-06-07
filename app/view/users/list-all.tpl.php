<h1 class='content-subhead'><?= $title ?></h1>

<?php if ( !empty($users) ) : ?>

<table class='pure-table pure-table-horizontal'>
	<thead>
		<tr>
			<th>#</th>
			<th>Acronym</th>
			<th>Email</th>
			<th>Active</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>

<?php foreach ( $users as $user ) : ?>

<?php

$status = null;

if ( empty($user->deleted) && empty($user->active) ) {
	$status = "<a href='" . $this->url->create('users/activate/' . $user->id) . "'><span class='fa fa-circle-o'></span></a>";
} else {
	$status = "<a href='" . $this->url->create('users/deactivate/' . $user->id) . "'><span class='fa fa-check-circle-o'></span></a>";
}

?>

	<tr>
		<td><?= $user->id ?></td>
		<td><a href='<?= $this->url->create('users/id/' . $user->id) ?>'><span class='fa fa-user'></span> <?= $user->acronym ?></a></td>
		<td><?= $user->email ?></td>
		<td><?= $status ?></td>
		<td><a href='<?= $this->url->create('users/update/' . $user->id) ?>'><span class='fa fa-edit'></span></a></td>
		<td><a href='<?= $this->url->create('users/soft-delete/' . $user->id) ?>'><span class='fa fa-trash-o'></span></a></td>
	</tr>

<?php endforeach; ?>

	</tbody>
</table>

<?php endif; ?>