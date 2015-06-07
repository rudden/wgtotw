<?php

namespace Anax\Form;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddAnswerToAnswer extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



    /**
     * Constructor
     *
     */
    public function __construct($answer)
    {
        parent::__construct([
                'fs_class' => 'pure-group'
            ],
            [
                'q_id' => [
                    'type'  => 'hidden',
                    'value' => $answer->question_id
                ],
                'a_id' => [
                    'type'  => 'hidden',
                    'value' => $answer->id
                ],
                'content' => [
                    'type'        => 'textarea',
                    'rows'        => '7',
                    'class'       => 'pure-input-1',
                    'required'    => true,
                    'placeholder' => 'Be helpful..',
                    'validation'  => ['not_empty'],
                ],
                'doCreateAnswerToAnswer' => [
                    'type'      => 'submit',
                    'class'     => 'pure-button pure-input-1 pure-button-primary',
                    'value'     => 'Skicka',
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
            'controller' => 'answers',
            'action'     => 'addAnswerToAnswer',
            'params'     => [
                'q_id' => $this->Value('q_id'),
                'a_id' => $this->Value('a_id')
            ]
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
