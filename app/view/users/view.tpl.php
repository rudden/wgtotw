<h1 class='content-subhead'><?= $user->acronym ?></h1>

<div class='pure-g'>
	<div class='pure-u-1-2'>
		<img class='user-avatar' alt='<?= $user->acronym ?>' height='48' width='48' src='http://www.gravatar.com/avatar/<?= md5(strtolower(trim($user->email))) ?>s=40'>
		<p><span class='fa fa-user'></span> <?= $user->name ?></p>
		<p><span class='fa fa-envelope-o'></span> <?= $user->email ?></p>
		<p><?= $edit ?></p>
	</div>
	<div class='pure-u-1-2'>

		<h1 class='content-subhead'>Posts created by <?= $user->acronym ?></h1>

		<?php if ( empty($questions) ) : ?>

		<?php 
			$this->fmsg->info('No posts created.. Yet.');
			echo $this->fmsg->printMessage(); 
		?>

		<?php endif; ?>

		<?php foreach ( $questions as $question ) : ?>
		
		<div class='box'>
			<a href='<?= $this->url->create('questions/view/' . $question->id . '/rating') ?>'><?= $question->title ?></a>
			<p class='post-meta'>Created <?= $question->created ?></p>
		</div>

		<?php endforeach; ?>

		<h1 class='content-subhead'>Answers created by <?= $user->acronym ?></h1>

		<?php if ( empty($answers) ) : ?>

		<?php 
			$this->fmsg->info('No answers created.. Yet.'); 
			echo $this->fmsg->printMessage(); 
		?>

		<?php endif; ?>

		<?php foreach ( $answers as $answer ) : ?>

		<div class='box'>
			<a href='<?= $this->url->create('questions/view/' . $answer->question_id . '/rating') ?>'>
				<?= $this->textFilter->doFilter(selectNoOfWords($answer->content, 7), 'markdown') ?>
			</a>
			<p class='post-meta'>Created <?= $answer->created ?></p>
		</div>

		<?php endforeach; ?>

	</div>
</div>