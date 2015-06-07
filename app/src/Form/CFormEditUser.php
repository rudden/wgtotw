<?php

namespace Anax\Form;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormEditUser extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



    /**
     * Constructor
     *
     */
    public function __construct($user)
    {
        parent::__construct([
                'class'  => 'pure-form pure-form-stacked'
            ],
            [
                'id' => [
                    'type'  => 'hidden',
                    'value' => $user->id,
                ],
                'name' => [
                    'value'      => $user->name,
                    'type'       => 'text',
                    'class'      => 'pure-input-2-3',
                    'label'      => 'Name:',
                    'required'   => true,
                    'validation' => ['not_empty'],
                ],
                'acronym' => [
                    'value'      => $user->acronym,
                    'type'       => 'text',
                    'class'      => 'pure-input-2-3',
                    'label'      => 'Username:',
                    'required'   => true,
                    'validation' => ['not_empty'],
                ],
                'email' => [
                    'value'      => $user->email,
                    'type'       => 'text',
                    'class'      => 'pure-input-2-3',
                    'label'      => 'Email:',
                    'required'   => true,
                    'validation' => ['not_empty', 'email_adress'],
                ],
                'password' => [
                    'value'      => $user->password,
                    'type'       => 'password',
                    'class'      => 'pure-input-2-3',
                    'label'      => 'Password:',
                    'required'   => true,
                    'validation' => ['not_empty'],
                ],
                'doSave' => [
                    'type'     => 'submit',
                    'class'    => 'pure-button pure-input-2-3 pure-button-primary',
                    'value'    => 'Save',
                    'callback' => [$this, 'callbackSubmit'],
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
            'controller' => 'users',
            'action'     => 'update',
            'params'     => [
                'id'   => $this->Value('id')
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