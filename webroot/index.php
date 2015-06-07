<?php

require __DIR__.'/config_with_app.php';

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

$app->theme->configure(ANAX_APP_PATH . 'config/theme.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar.php');

$app->router->add('', function() use ($app) {

	$app->theme->setTitle('Start');

	if ( !$app->session->has('user') ) {
		$app->dispatcher->forward(['controller' => 'c-form', 'action' => 'index', 'params' => ['\Anax\Form\CFormLoginUser', '', 'Login', 'Welcome back!', 'questions', 'login']]);
		$app->dispatcher->forward(['controller' => 'c-form', 'action' => 'index', 'params' => ['\Anax\Form\CFormAddUser', '', 'Register a new account', 'Welcome!', 'questions', 'register']]);
	}
	else {
		$app->dispatcher->forward(['controller' => 'c-form', 'action' => 'index', 'params' => ['\Anax\Form\CFormAddUser', '', 'Create a new account', 'Welcome!', 'questions', 'form']]);
	}

	$latestQuestions = $app->questions->findLatestQuestions(3);

	$app->views->add('users/user', ['sUser' => $app->session->get('user')], 'user');
	$app->views->add('main/top', [], 'top');
	$app->views->add('default/flash', [], 'flash');
	$app->views->add('questions/list', ['title' => 'Latest posts', 'items' => $latestQuestions], 'box-1');
	$app->dispatcher->forward(['controller' => 'tags', 'action' => 'get-top-tags']);
	$app->dispatcher->forward(['controller' => 'users', 'action' => 'get-top-users-answers']);
	$app->dispatcher->forward(['controller' => 'users', 'action' => 'get-top-users-questions']);

	
});

$app->router->add('login', function() use ($app) {

	$app->theme->setTitle('Login');

	if ( !$app->session->has('user') ) {
		$app->dispatcher->forward(['controller' => 'c-form', 'action' => 'index', 'params' => ['\Anax\Form\CFormLoginUser', '', 'Login', '', 'users', 'posts']]);
		$app->views->add('default/flash', [], 'flash');
	}
	else {
		$app->fmsg->info('You are already logged in.');
		$app->views->add('default/flash', [], 'flash');
	}

});

$app->router->add('questions', function() use ($app) {

	$app->theme->setTitle('Questions');

	$tags = $app->tags->returnTagsAsArray();

	$app->dispatcher->forward([
		'controller' => 'c-form',
		'action'     => 'index',
		'params'     => ['\Anax\Form\CFormAddQuestion', $tags, 'Create a new post', 'What is on your mind?', 'questions', 'form']
	]);

	$app->dispatcher->forward(['controller' => 'questions', 'action' => 'show']);
	$app->views->add('users/user', ['sUser' => $app->session->get('user')], 'user');

});

$app->router->add('tags', function() use ($app) {

	$app->theme->setTitle('Tags');

	$app->dispatcher->forward(['controller' => 'tags', 'action' => 'show']);
	$app->views->add('users/user', ['sUser' => $app->session->get('user')], 'user');

});

$app->router->add('users', function() use ($app) {

	$app->theme->setTitle('Users');

	$app->dispatcher->forward(['controller' => 'users', 'action' => 'show']);
	$app->views->add('users/user', ['sUser' => $app->session->get('user')], 'user');

});

$app->router->add('about', function() use ($app) {

	$app->theme->setTitle('About');

	$about  = $app->fileContent->get('about.md');
	$byline = $app->fileContent->get('byline.md');
	$about  = $app->textFilter->doFilter($about, 'markdown');
	$byline = $app->textFilter->doFilter($byline, 'markdown');

	$app->views->add('main/page', ['content' => $about, 'byline' => $byline], 'posts');
	$app->views->add('users/user', ['sUser'   => $app->session->get('user')], 'user');
	
});

$app->router->handle();
$app->theme->render();