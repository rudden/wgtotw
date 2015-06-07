<?php if ( !empty($tags) ) : ?>

<h1 class='content-subhead'><?= $title ?></h1>

<section class='tags'>

<?php foreach ( $tags as $tag ) : ?>

<?php $no_of_questions = $this->questions->findNoOfQuestionsRelatedToTag($tag->tag); ?>

<div class='tag-box'>
	<p>
		<span class='fa fa-tags'></span>
		<a href='<?= $this->url->create('questions/tag/' . $tag->tag) ?>'><?= $tag->tag ?></a>
		<span class='pull-right'><?= $no_of_questions[0]->no ?><?php $no_of_questions[0]->no > 1 ? $text = ' posts' : $text = ' post'; ?><?= $text ?></span>
	</p>
	<p><?= $tag->about ?></p>
</div>

<?php endforeach; ?>

</section>

<?php endif; ?>