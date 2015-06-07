<p>
    <a href="#<?= $name ?>" role="button" class="pure-button-primary pure-button" data-toggle="modal">
        <?= $title ?>
    </a>
</p>

<div id="<?= $name ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="formLabel" aria-hidden="true">
    <div class="modal-header">
        <h1 id="formLabel"><?= $title ?></h1>
    </div>

    <div class="modal-body">
        <p>
            <?= $body ?>
        </p>

        <?= $content ?>
    </div>

    <div class="modal-footer">
        <button class="pure-button" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>