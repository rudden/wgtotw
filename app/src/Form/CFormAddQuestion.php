<?php

namespace Anax\Form;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddQuestion extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



    /**
     * Constructor
     *
     */
    public function __construct($tags)
    {
        parent::__construct([
                'fs_class' => 'pure-group'
            ],
            [
                'title' => [
                    'type'        => 'text',
                    'class'       => 'pure-input-1',
                    'required'    => true,
                    'placeholder' => 'Titel',
                    'validation'  => ['not_empty'],
                ],
                'content' => [
                    'type'        => 'textarea',
                    'rows'        => '10',
                    'class'       => 'pure-input-1',
                    'required'    => true,
                    'placeholder' => '...',
                    'validation'  => ['not_empty'],
                ],
                'tags' => [
                    'type'   => 'checkbox-multiple',
                    'values' => $tags,
                ],
                'doCreate' => [
                    'type'      => 'submit',
                    'class'     => 'pure-button pure-input-1 pure-button-primary',
                    'value'     => 'Skapa',
                    'callback'  => [$this, 'callbackSubmit'],
            ],
        ]);
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        $this->di->dispatcher->forward([
            'controller' => 'questions',
            'action'     => 'add',
            'params'     => ['page' => $this->di->request->extractRoute()],
        ]);
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->AddOUtput("<p><i>Form was submitted and the callback method returned true.</i></p>");
        $this->redirectTo();
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        $this->redirectTo();
    }
}
