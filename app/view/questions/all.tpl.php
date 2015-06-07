<?php if ( empty($questions) ) : ?>

    <?= $this->fmsg->printMessage() ?>

<?php endif; ?>

<?php if ( !empty($questions) ) : ?>

<h1 class='content-subhead'><?= $title ?></h1>

<?php if ( !empty($image) ) : ?>
    
<img class='pure-img' src='../../img/tags/<?= $image ?>' height='440'>

<?php endif; ?>

<?php foreach ($questions as $question) : ?>

<?php 
    $user = $this->users->findUserById($question->user_id);
    $no   = $this->answers->findAndCountAnswers($question->id);
?>

<section class='post'>

    <?php foreach ($user as $creator) : ?>
    
    <header class='post-header'>
        <img class='post-avatar' alt='<?= $creator->acronym ?>' height='48' width='48' src='http://www.gravatar.com/avatar/<?= md5(strtolower(trim($creator->email))) ?>s=40'>
        <h2 class='post-title'>
            <a href='<?= $this->url->create('questions/view/' . $question->id . '/rating')?>'>
                <?= $question->title ?>
            </a>
        </h2>

        <p class='post-meta'>
            By <a class='post-author' href='<?= $this->url->create('users/id/' . $creator->id) ?>'><?= $creator->acronym ?></a> within 
            
            <?php $tags = explode(',', $question->tags); ?>
            <?php foreach ($tags as $tag) : ?>
                <a class='post-tag post-tag-<?= $tag ?>' href='<?= $this->url->create('questions/tag/' . $tag) ?>'><?= $tag ?></a>
            <?php endforeach; ?>
        </p>

    </header>

    <div class='post-description'>
        <p>
            <?= $this->textFilter->doFilter(selectNoOfWords($question->content, 15), 'markdown') ?>
            <a href='<?= $this->url->create('questions/view/' . $question->id . '/rating') ?>'>continue reading</a>
        </p>
    </div>

    <div class='post-footer'>
        <p><?= $no[0]->amount ?><?php $no[0]->amount > 1 ? $text = ' answers' : $text = ' answer'; ?><?= $text ?> and <?= $question->rating ?> in rating value</p>
    </div>

    <?php endforeach; ?>

</section>

<?php endforeach; ?>

<?php endif; ?>