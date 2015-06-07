<?php $_user = $this->users->findUserByAcronym($sUser['name']) ?>

<div class='pure-menu pure-menu-horizontal'>
    <ul class='pure-menu-list'>
        <li class='pure-menu-item pure-menu-has-children pure-menu-allow-hover'>
            <a href='#' id='menuLink1' class='pure-menu-link'><?= $_user->acronym ?></a>
            <ul class='pure-menu-children'>
                <li class='pure-menu-item'><a href='<?= $this->url->create('users/id/' . $_user->id) ?>' class='pure-menu-link'>Profile</a></li>
                <li class='pure-menu-item'><a href='<?= $this->url->create('questions/user/' . $_user->id) ?>' class='pure-menu-link'>My posts</a></li>

                <?php if ( $_user->acronym === 'admin' ) : ?>
                <li class='pure-menu-item'><a href='<?= $this->url->create('users/list') ?>' class='pure-menu-link'>Users</a></li>
                <? endif; ?>

                <li class='pure-menu-item'><a href='<?= $this->url->create('users/logout') ?>' class='pure-menu-link'>Logout</a></li>
            </ul>
        </li>
    </ul>
</div>