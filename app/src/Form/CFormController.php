<?php

namespace Anax\Form;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;




    /**
     * Index action using external form.
     *
     * @param string $cform which form to show/create¨
     * @param string $param a parameter for the form class
     * @param string $title set the title of the form
     * @param string $body set the body of the form
     * @param string $view which view to fetch form from
     * @param string $region in which region to place the form, main is default
     *
     * @return void
     */
    public function indexAction($cform, $param, $title, $body, $view, $region = 'main')
    {
        $this->di->session();

        $form = new $cform($param);
        $form->setDI($this->di);
        $form->check();

        $this->di->views->add('' . $view . '/form', [
            'name'    => md5(uniqid(rand(), true)),
            'body'    => $body,
            'title'   => $title,
            'content' => $form->getHTML()
        ], $region);
    }
}