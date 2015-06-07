<?php if ( !empty($question) ) : ?>

<h1 class='content-subhead'>Created <?= $question->created ?></h1>

<section class='post'>
    
    <header class='post-header'>
        <img class='post-avatar' alt='<?= $user[0]->acronym ?>' src='http://www.gravatar.com/avatar/<?= md5(strtolower(trim($user[0]->email))) ?>s=40'>
        <p class='post-avatar'>
            <a href='<?= $this->url->create('questions/increment/' . $question->id) ?>'>
                <span class="fa fa-chevron-up"></span>
            </a>
            <?= $question->rating ?>
            <a href='<?= $this->url->create('questions/decrement/' . $question->id) ?>'>
                <span class="fa fa-chevron-down"></span>
            </a>
        </p>
        <h2 class='post-title'><?= $question->title ?></h2>

        <p class='post-meta'>
            By <a class='post-author' href='<?= $this->url->create('users/id/' . $user[0]->id) ?>'><?= $user[0]->acronym ?></a> within 
            
            <?php $tags = explode(',', $question->tags); ?>
            <?php foreach ($tags as $tag) : ?>
                <a class='post-tag post-tag-<?= $tag ?>' href='<?= $this->url->create('questions/tag/' . $tag) ?>'><?= $tag ?></a>
            <?php endforeach; ?>
        </p>
    </header>

    <div class='post-description'>
        <p>
            <?= $this->textFilter->doFilter($question->content, 'shortcode, markdown') ?>
        </p>
    </div>

    <?php if ( $this->session->has('user') ) : ?>

        <?php $q_name = md5(uniqid(rand(), true)) ?>

        <p>
            <a href="#<?= $q_name ?>" role="button" data-toggle="modal">
                <span class="fa fa-reply"></span>
            </a>
        </p>

        <div id="<?= $q_name ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="formLabel" aria-hidden="true">
            <div class="modal-header">
                <h1 id="formLabel"><?= $title ?></h1>
            </div>

            <div class="modal-body">
                <p>
                    Answer the question: <?= $question->title ?>
                </p>

                <?= $a_form ?>
            </div>

            <div class="modal-footer">
                <button class="pure-button" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
        </div>

    <?php endif; ?>

    <?php if ( !empty($answers) ) : ?>
    
    <?php if ( !empty($no_of_answers) ) : ?>

    <div class='pure-g'>
        <div class='pure-u-1-2'>
            <h4><?= $no_of_answers ?> <?php $no_of_answers > 1 ? $text = ' answers' : $text = ' answer'; ?><?= $text ?></h4>
        </div>
        <div class='pure-u-1-2'>
            <div class='pure-menu pure-menu-horizontal'>
                <ul class='pure-menu-list'>
                    <li class='pure-menu-item pure-menu-has-children pure-menu-allow-hover'>
                        <a href='#' id='menuLink1' class='pure-menu-link'>Sort answers</a>
                        <ul class='pure-menu-children'>
                            <li class='pure-menu-item'>
                                <a href='<?= $this->url->create('questions/view/' . $question->id . '/rating') ?>' class='pure-menu-link'>Rating</a>
                            </li>
                            <li class='pure-menu-item'>
                                <a href='<?= $this->url->create('questions/view/' . $question->id . '/created') ?>' class='pure-menu-link'>Created datetime</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <?php endif; ?>

    <?php foreach ($answers as $answer) : ?>

    <?php $a_user = $this->users->findUserById($answer->user_id) ?>

    <?php foreach ($a_user as $creator) : ?>

    <?php

        $sUser          = null;
        $acceptedStatus = null;

        $this->session->has('user') ? $sUser = $this->session->get('user') : $sUser = null;

        if ( empty($answer->accepted) && $user[0]->acronym === $sUser['name'] ) {
            $acceptedStatus = "<div class='post-footer'><p>mark as accepted <a href='" . $this->url->create('answers/acceptedAnswer/' . $question->id . '/' . $answer->id) . "'><span class='fa fa-circle-thin'></span></a></p></div>";
        }
        elseif ( !empty($answer->accepted) && $user[0]->acronym === $sUser['name'] ) {
            $acceptedStatus = "<div class='post-footer'><p>unmark as accepted <a href='" . $this->url->create('answers/unacceptedAnswer/' . $question->id . '/' . $answer->id) . "'><span class='fa fa-check-circle-o'></span></a></p></div>";
        }
        elseif ( !empty($answer->accepted) ) {
            $acceptedStatus = "<div class='post-footer'><p>accepted by creator <span class='fa fa-check-circle-o'></span></p></div>";
        }

    ?>

    <div class='post-answer'>
        <img class='post-avatar' alt='<?= $creator->acronym ?>' height='48' width='48' src='http://www.gravatar.com/avatar/<?= md5(strtolower(trim($creator->email)))?>s=40'>
        <p class='post-avatar'>
            <a href='<?= $this->url->create('answers/increment/' . $answer->id . '/' . $question->id . '/rating') ?>'>
                <span class="fa fa-chevron-up"></span>
            </a>
            <?= $answer->rating ?>
            <a href='<?= $this->url->create('answers/decrement/' . $answer->id . '/' . $question->id . '/rating') ?>'>
                <span class="fa fa-chevron-down"></span>
            </a>
        </p>
        <p class='post-meta'>
            By <a class='post-author' href='<?= $this->url->create('users/id/' . $creator->id) ?>'><?= $creator->acronym ?></a>
            the <?= $answer->created ?>
        </p>
        <div class='post-text'>
            <?= $this->textFilter->doFilter($answer->content, 'shortcode, markdown') ?>
        </div>
        <?= $acceptedStatus ?>
    </div>

    <?php if ( $this->session->has('user') ) : ?>

        <?php $a_name = md5(uniqid(rand(), true)) ?>

        <?php
            $_a_form = new \Anax\Form\CFormAddAnswerToAnswer($answer);
            $_a_form->setDI($this->di);
            $_a_form->check();
        ?>

        <p class='answer'>
            <a href="#<?= $a_name ?>" role="button" data-toggle="modal">
                <span class="fa fa-reply"></span>
            </a>
        </p>

        <div id="<?= $a_name ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="formLabel" aria-hidden="true">
            <div class="modal-header">
                <h1 id="formLabel"><?= $title ?></h1>
            </div>

            <div class="modal-body">
                <p>
                    Answer to answer #<?= $answer->id ?> related to question: <?= $question->title ?>
                </p>

                <?= $_a_form->getHTML() ?>

            </div>

            <div class="modal-footer">
                <button class="pure-button" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
        </div>

    <?php endif; ?>

    <?php $_answers = $this->answers->findAnswersRelatedToAnswer($answer->id) ?>

    <?php if ( !empty($_answers) ) : foreach ($_answers as $_answer) : ?>

    <?php $_a_user = $this->users->findUserById($_answer->user_id) ?>

    <?php foreach ($_a_user as $_creator) : ?>

    <?php
        $sUser           = null;
        $_acceptedStatus = null;

        $this->session->has('user') ? $sUser = $this->session->get('user') : $sUser = null;

        if ( empty($_answer->accepted) && $user[0]->acronym === $sUser['name'] ) {
            $_acceptedStatus = "<div class='post-footer'><p>mark as accepted <a href='" . $this->url->create('answers/acceptedAnswer/' . $question->id . '/' . $_answer->id) . "'><span class='fa fa-circle-thin'></span></a></p></div>";
        }
        elseif ( !empty($_answer->accepted) && $user[0]->acronym === $sUser['name'] ) {
            $_acceptedStatus = "<div class='post-footer'><p>unmark as accepted <a href='" . $this->url->create('answers/unacceptedAnswer/' . $question->id . '/' . $_answer->id) . "'><span class='fa fa-check-circle-o'></span></a></p></div>";
        }
        elseif ( !empty($_answer->accepted) ) {
            $_acceptedStatus = "<div class='post-footer'><p>accepted by creator <span class='fa fa-check-circle-o'></span></p></div>";
        }
    ?>

    <div class='post-answer-answer'>
        <img class='post-avatar' alt='<?= $_creator->acronym ?>' height='48' width='48' src='http://www.gravatar.com/avatar/<?= md5(strtolower(trim($_creator->email)))?>s=40'>
        <p class='post-avatar'>
            <a href='<?= $this->url->create('answers/increment/' . $_answer->id . '/' . $question->id) ?>'>
                <span class="fa fa-chevron-up"></span>
            </a>
            <?= $_answer->rating ?>
            <a href='<?= $this->url->create('answers/decrement/' . $_answer->id . '/' . $question->id) ?>'>
                <span class="fa fa-chevron-down"></span>
            </a>
        </p>
        <p class='post-meta'>
            By <a class='post-author' href='<?= $this->url->create('users/id/' . $_creator->id) ?>'><?= $_creator->acronym ?></a>
            den <?= $_answer->created ?>

        </p>
        <div class='post-text'>
            <?= $this->textFilter->doFilter($_answer->content, 'shortcode, markdown') ?>
        </div>
        <?= $_acceptedStatus ?>
    </div>

    <?php endforeach; ?>

    <?php endforeach; endif; ?>

    <?php endforeach; ?>

    <?php endforeach; ?>

    <?php endif; ?>

    <p>
        <a href='<?= $this->url->create('questions') ?>'> 
            back to all posts
        </a>
    </p>

</section>

<?php endif; ?>