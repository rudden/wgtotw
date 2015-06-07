<!doctype html>
<html lang='<?= $lang ?>'>
<head>
<meta charset='utf-8'/>
<title><?= $title . $title_append ?></title>
<?php if ( isset($favicon) ): ?>
<link rel='icon' href='<?= $this->url->asset($favicon) ?>'/>
<?php endif; ?>
<?php foreach ( $stylesheets as $stylesheet ) : ?>
<link rel='stylesheet' type='text/css' href='<?= $this->url->asset($stylesheet) ?>'/>
<?php endforeach; ?>
<?php if ( isset($style) ) : ?>
<style><?= $style ?></style>
<?php endif; ?>
<script src='<?= $this->url->asset($modernizr) ?>'></script>
</head>

<body>

<div id='layout' class='pure-g'>

<?php if ( $this->views->hasContent('navbar') ) : ?>
<div class='sidebar pure-u-1 pure-u-md-1-4'>
<div class='header'>
<?php $this->views->render('header')?>
<?php $this->views->render('navbar')?>
</div>
</div><!-- end sidebar -->
<?php endif; ?>

<div class='content pure-u-1 pure-u-md-3-4'>

<div>

<?php if ( $this->session->has('flashmessage') ) : ?>
<div class='pure-g'>
	<div class='pure-u-1'><?php $this->views->render('flash') ?></div>
</div>
<?php endif; ?>

<?php if ( $this->session->has('user') ) : ?>
<div class='pure-g'>
	<div class='pure-u-1-2'><?php $this->views->render('form') ?></div>
    <div class='pure-u-1-2'><?php $this->views->render('user') ?></div>
</div>
<?php endif; ?>

<?php if ( $this->views->hasContent('register') ) : ?>
<div class='pure-g'>
	<div class='pure-u-1-2'><?php $this->views->render('login') ?></div>
	<div class='pure-u-1-2'><?php $this->views->render('register') ?></div>
</div>
<?php endif; ?>

<?php if ( $this->views->hasContent('top') ) : ?>
<div class='pure-g'>
	<div class='pure-u-1'><?php $this->views->render('top') ?></div>
</div>
<?php endif; ?>

<?php if ( $this->views->hasContent('main-1', 'main-2') ) : ?>
<div class='pure-g'>
	<div class='pure-u-1-2'><?php $this->views->render('main-1') ?></div>
  <div class='pure-u-1-2'><?php $this->views->render('main-2') ?></div>
</div>
<?php endif; ?>

<?php if ( $this->views->hasContent('box-1', 'box-2', 'box-3', 'box-4') ) : ?>
<div class='pure-g'>
	<div class='pure-u-1-4'><?php $this->views->render('box-1') ?></div>
    <div class='pure-u-1-4'><?php $this->views->render('box-2') ?></div>
    <div class='pure-u-1-4'><?php $this->views->render('box-3') ?></div>
    <div class='pure-u-1-4'><?php $this->views->render('box-4') ?></div>
</div>
<?php endif; ?>

<?php if ( $this->views->hasContent('posts') ) : ?>

<div class='posts'>
<?php $this->views->render('posts')?>
</div>

<?php endif; ?>

<?php if ( isset($footer) ) echo $footer?>
<div class='footer'>
<?php $this->views->render('footer')?>
</div>

</div><!-- end div -->

</div><!-- end content -->

</div><!-- end layout -->

<?php if ( isset($jquery) ) : ?>
<script src='<?= $this->url->asset($jquery) ?>'></script>
<?php endif; ?>

<?php if ( isset($javascript_include) ) : foreach ($javascript_include as $val) : ?>
<script src='<?= $this->url->asset($val) ?>'></script>
<?php endforeach; endif; ?>

<?php if ( isset($google_analytics) ) : ?>
<script>
  var _gaq=[['_setAccount','<?=$google_analytics?>'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
  s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
<?php endif; ?>

</body>
</html>