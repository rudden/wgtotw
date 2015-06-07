<?php

namespace Anax\Questions;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class QuestionsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;




    /**
     * Show all questions.
     *
     * @return void
     */
    public function showAction()
    {
        $questions = $this->questions->findQuestions();

        $this->views->add('questions/all', ['questions' => $questions, 'title' => 'All posts']);
    }



    /**
     * View single question and it's related data
     *
     * @param string $id which question to view
     *
     * @return void
     */
    public function viewAction($id = null, $order = null)
    {
        $this->theme->setTitle('Show post');
        
        $question      = $this->questions->find($id);
        $answer        = $this->answers->findAnswer($id);
        $answers       = $this->answers->findAnswers($question->id, $order);
        $no_of_answers = $this->answers->findAndCountAnswers($question->id);
        $user          = $this->users->findUserById($question->user_id);

        $form = new \Anax\Form\CFormAddAnswer($question);
        $form->setDI($this->di);
        $form->check();

        $this->views->add('questions/view', [
            'question'      => $question,
            'answers'       => $answers,
            'no_of_answers' => $no_of_answers[0]->amount,
            'title'         => 'Create an answer',
            'a_form'        => $form->getHTML(),
            'user'          => $user
        ], 'posts');

        $this->views->add('users/user', ['sUser' => $this->session->get('user')], 'user');
    }



    /**
     * View questions from a specific tag
     *
     * @param string $tag which tag to show questions from
     *
     * @return void
     */
    public function tagAction($tag = null)
    {
        $this->theme->setTitle('Tag ' . $tag);

        $questions = $this->questions->findQuestionsRelatedToTag($tag);

        $image = $this->tags->findTagFromName($tag);

        if ( empty($questions) ) {
            $this->fmsg->warning('No posts found.');
        }

        $this->views->add('questions/all', ['questions' => $questions, 'title' => $tag, 'image' => $image[0]->image]);
        $this->views->add('users/user', ['sUser' => $this->session->get('user')], 'user');
    }



    /**
     * View questions from a specific user
     *
     * @param int $id which user to show questions from
     *
     * @return void
     */
    public function userAction($id = null)
    {
        $this->theme->setTitle('Posts');

        $user      = $this->users->findUserById($id);
        $questions = $this->questions->findQuestionsRelatedToUser($id);

        if ( !empty($questions) ) {
            $this->views->add('questions/all', ['questions' => $questions, 'title' => 'Posts created by ' . $user[0]->acronym]);
        } 
        else {
            $flash = $this->fmsg->warning('No posts found.');
            $this->views->add('default/flash', ['flash' => $flash]);
        }
        
        $this->views->add('users/user', ['sUser' => $this->session->get('user')], 'user');
    }



    /**
     * Add a question.
     *
     * @return void
     */
    public function addAction()
    {
        $time = gmdate("Y-m-d H:i:s");

        $tags = implode(',', $this->request->getPost('tags'));
        $user = $this->session->get('user');
            
        $this->questions->save([
            'user_id'  => $user['id'],
            'title'    => $this->request->getPost('title'),
            'content'  => $this->request->getPost('content'),
            'tags'     => $tags,
            'rating'   => 0,
            'created'  => $time
        ]);

        $url = $this->url->create('questions');
        $this->response->redirect($url); 
    }



    /**
     * Edit/Update a question.
     *
     * @param string $id which question to update
     * @param string $page which page the question is located on for redirecting purposes
     * 
     * @return void
     */
    public function updateAction($id = null)
    {
        $this->di->theme->setTitle("Uppdatera kommentar");

        if ($this->request->getPost('doUpdate')) {

            $question = $this->questions->find($id);

            $this->di->dispatcher->forward([
                'controller' => 'c-form',
                'action'     => 'index',
                'params'     => ['\Anax\HTMLForm\CFormEditQuestion', $question, 'Uppdatera en kommentar', 'questions', 'posts']
            ]);

        }
        
        elseif ($this->request->getPost('doSave')) {

            $time = gmdate("Y-m-d H:i:s");

            $comment = $this->questions->find($id);

            $this->questions->save([
                'id'      => $comment->id,
                'name'    => $this->request->getPost('name'),
                'email'   => $this->request->getPost('email'),
                'content' => $this->request->getPost('content'),
                'updated' => $time,
            ]);

            $url = $this->url->create($comment->page);
            $this->response->redirect($url);
        }
    }



    /**
     * Remove single question.
     *
     * @param string $id which question to delete
     * @param string $page which page the question is located on for redirecting purposes
     * 
     * @return void
     */
    public function removeAction($id = null, $page = null)
    {
        $res = $this->questions->delete($id);

        $url = $this->url->create($page);
        $this->response->redirect($url);
    }



    /**
     * Increment question
     *
     * @param string $id which question to increment
     *
     * @return void
     */
    public function incrementAction($id = null)
    {
        if ( !$this->session->has('user') ) {
            
            $this->fmsg->warning('You need to log in to be able to upvote posts!');
            $url = $this->url->create('login');
            $this->response->redirect($url);

        }
        else {
            
            $question = $this->questions->find($id);
            $this->questions->save(['id' => $question->id, 'rating' => ($question->rating + 1)]);
            
        }

        $this->response->redirect($this->url->create('questions/view/' . $id . '/rating'));
    }



    /**
     * Decrement question
     *
     * @param string $id which question to decrement
     *
     * @return void
     */
    public function decrementAction($id = null)
    {
        if ( !$this->session->has('user') ) {
            
            $this->fmsg->warning('You need to be log in to be able to downvote posts!');
            $url = $this->url->create('login');
            $this->response->redirect($url);

        }
        else {
            
            $question = $this->questions->find($id);
            $this->questions->save(['id' => $question->id, 'rating' => ($question->rating - 1)]);

        }

        $this->response->redirect($this->url->create('questions/view/' . $id . '/rating'));
    }
}
