<?php
/**
 * Config file for pagecontrollers, creating an instance of $app.
 *
 */

// Get environment & autoloader.
require __DIR__.'/config.php'; 

// Create services and inject into the app. 
$di  = new \Anax\DI\CDIFactoryDefault();

// Services brought in through composer
$di->setShared('fmsg', function() use ($di) {
    $fmsg = new rudden\Flash\FlashMessages();
    $fmsg->setDI($di);
    return $fmsg;
});

// Services buildt for this specific project
$di->setShared('questions', function() use ($di) {
    $questions = new \Anax\Questions\Questions();
    $questions->setDI($di);
    return $questions;
});

$di->setShared('answers', function() use ($di) {
    $answers = new \Anax\Answers\Answers();
    $answers->setDI($di);
    return $answers;
});

$di->setShared('tags', function() use ($di) {
    $tags = new \Anax\Tags\Tags();
    $tags->setDI($di);
    return $tags;
});

$di->setShared('users', function() use ($di) {
    $users = new \Anax\Users\Users();
    $users->setDI($di);
    return $users;
});

$app = new \Anax\MVC\CApplicationBasic($di);