<?php

namespace Anax\Answers;

/**
 * Answers connected to comments or other content
 * 
 */
class AnswersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;




    /**
     *	Add an answer to a question
     *
     * 	@param int $q_id which question the answer relates to
     *
     * 	@return void
     */
    public function addAnswerToQuestionAction($q_id)
    {
    	$time = gmdate('Y-m-d H:i:s');

        $user = $this->session->get('user');

        $this->db->insert('answers', ['user_id', 'answer_id', 'question_id', 'content', 'rating', 'created']);
        $this->db->execute([$user['id'], null, $q_id, $this->request->getPost('content'), 0, $time]);

        $url = $this->url->create('questions/view/' . $q_id . '/rating');
        $this->response->redirect($url);
    }



    /**
     *  Add an answer to an answer
     *
     *  @param int $q_id which question the answer relates to
     *  @param int $a_id which answer to connect the answer to
     *
     *  @return void
     */
    public function addAnswerToAnswerAction($q_id, $a_id)
    {
        $time = gmdate('Y-m-d H:i:s');

        $user = $this->session->get('user');

        $this->db->insert('answers', ['user_id', 'answer_id', 'question_id', 'content', 'rating', 'created']);
        $this->db->execute([$user['id'], $a_id, null, $this->request->getPost('content'), 0, $time]);
    }



    /**
     * Accept an answer
     * 
     * @param  int $q_id which question the answer relates to
     * @param  int $a_id which answer to accept
     * 
     * @return void
     */
    public function acceptedAnswerAction($q_id, $a_id)
    {
        $time = gmdate('Y-m-d H:i:s');

        $answer = $this->answers->find($a_id);

        $answer->accepted = $time;
        $answer->save();

        $url = $this->url->create('questions/view/' . $q_id . '/rating');
        $this->response->redirect($url);
    }



    /**
     * Not an accepted answer
     * 
     * @param  int $q_id which question the answer relates to
     * @param  int $a_id which answer to set to not accepted
     * 
     * @return void
     */
    public function unacceptedAnswerAction($q_id, $a_id)
    {
        $time = gmdate('Y-m-d H:i:s');

        $answer = $this->answers->find($a_id);

        $answer->accepted = null;
        $answer->save();

        $url = $this->url->create('questions/view/' . $q_id . '/rating');
        $this->response->redirect($url);
    }



    /**
     * Increment answer
     *
     * @param string $a_id which answer to increment
     * @param string $q_id the question id for redirecting
     *
     * @return void
     */
    public function incrementAction($a_id = null, $q_id = null)
    {
        if ( !$this->session->has('user') ) {
            
            $this->fmsg->warning('You need to log in to be able to upvote answers!');
            $url = $this->url->create('login');
            $this->response->redirect($url);

        }
        else {
            
            $answer = $this->answers->find($a_id);
            $this->answers->save(['id' => $answer->id,'rating' => ($answer->rating + 1)]);
            
        }

        $this->response->redirect($this->url->create('questions/view/' . $q_id . '/rating'));
    }



    /**
     * Decrement answer
     *
     * @param string $a_id which answer to decrement
     * @param string $q_id the question id for redirecting
     *
     * @return void
     */
    public function decrementAction($a_id = null, $q_id = null)
    {
        if ( !$this->session->has('user') ) {
            
            $this->fmsg->warning('You need to log in to be able to downvote answers!');
            $url = $this->url->create('login');
            $this->response->redirect($url);

        }
        else {
            
            $answer = $this->answers->find($a_id);
            $this->answers->save(['id' => $answer->id, 'rating' => ($answer->rating - 1)]);
            
        }

        $this->response->redirect($this->url->create('questions/view/' . $q_id . '/rating'));
    }
}