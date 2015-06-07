<?php

namespace Anax\Form;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormEditComment extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



    /**
     * Constructor
     *
     */
    public function __construct($comment)
    {
        parent::__construct([], [
            'id' => [
                'type'  => 'hidden',
                'value' => $comment->id,
            ],
            'name' => [
                'type'       => 'text',
                'required'   => true,
                'value'      => $comment->name,
                'validation' => ['not_empty'],
            ],
            'email' => [
                'type'       => 'text',
                'required'   => true,
                'value'      => $comment->email,
                'validation' => ['not_empty'],
            ],
            'content' => [
                'type'        => 'textarea',
                'required'    => true,
                'value'       => $comment->content,
                'placeholder' => 'Kommentar..',
                'validation'  => ['not_empty'],
            ],
            'doSave' => [
                'type'      => 'submit',
                'value'     => 'Spara',
                'callback'  => [$this, 'callbackSubmit'],
            ],
        ]);
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns false.
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
            'controller' => 'comment',
            'action'     => 'update',
            'params'     => [
                'id'   => $this->Value('id')
            ],
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