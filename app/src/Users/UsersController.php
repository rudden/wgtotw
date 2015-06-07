<?php

namespace Anax\Users;

/**
 *	A controller for users and admin related events.
 * 
 */
class UsersController implements \Anax\DI\IInjectionAware
{
	use \Anax\DI\TInjectable;




	/**
	 *	List all users
	 *	
	 * 	@return void
	 */
	public function listAction()
	{
		$active = $this->session->get('user');
		$active['name'] != 'admin' ? $this->response->redirect($this->url->create('')) : null;

		$all = $this->users->findAll();

		$this->theme->setTitle('Users');

		$this->views->add('users/list-all', ['users' => $all, 'title' => 'All users'], 'posts');
		$this->views->add('users/user', ['sUser' => $this->session->get('user')], 'user');
		$this->views->add('users/menu', [], 'posts');
	}



	/**
     * Show all users
     *
     * @return void
     */
    public function showAction()
    {
        $users = $this->users->findAllUsers();

        $this->views->add('users/all', ['title' => 'All users', 'users' => $users]);
    }



	/**
	 *	List one user with id
	 *
	 * 	@param int $id of user to display
	 * 	
	 * 	@return void
	 */
	public function idAction($id = null)
	{
		$user = $this->users->find($id);

		$this->theme->setTitle('User ' . $user->acronym);

		$s_user = $this->session->get('user');

		$edit = null;
		if ( $user->id === $s_user['id'] ) {
			$edit = "Edit user <a href='" . $this->url->create('users/update/' . $user->id) . "'><span class='fa fa-edit'></span></a>";
		}

		$answers   = $this->answers->findAnswersRelatedToUser($user->id);
		$questions = $this->questions->findQuestionsRelatedToUser($user->id);

		$this->views->add('users/view', ['user' => $user, 'questions' => $questions, 'answers' => $answers, 'edit' => $edit]);
	}



	/**
	 * Find user with id
	 *
	 * @param int $id which user
	 *
	 * @return array of user data
	 */
	public function findAction($id = null)
	{
		$user = $this->users->find($id);
		return $user;
	}



	/**
	 *	Add a new user
	 *
	 * 	@param string $acronym for the new user
	 * 	
	 *  @return void
	 */
	public function addAction($acronym = null)
	{
	
		$this->di->theme->setTitle('Create user');

		if ( $this->request->getPost('doCreateUser') ) {
			
			$time = gmdate('Y-m-d H:i:s');

			$this->users->save([
				'acronym'  => $this->request->getPost('acronym'),
				'email'    => $this->request->getPost('email'),
				'name'     => $this->request->getPost('name'),
				'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
				'created'  => $time,
				'active'   => $time
			]);

			$url = $this->url->create('users/id/' . $this->users->id);
			$this->response->redirect($url);

		}
		else {

			$this->dispatcher->forward([
				'controller' => 'c-form',
				'action'     => 'index',
				'params'     => ['\Anax\Form\CFormAddUser', '', 'Create a new user', '', 'users', 'posts']
			]);

		}

		$user = $this->session->get('user');

		$this->views->add('users/user', ['sUser' => $this->session->get('user')], 'user');
        $user['name'] === 'admin' ? $this->views->add('users/menu', [], 'posts') : false;
	}



	/**
	 *	Update an existing row
	 *	
	 * 	@param string $id of the row to update
	 *
	 * 	@return void
	 */
	public function updateAction($id = null)
    {
     
        $this->di->theme->setTitle('Update user');
        
        if ( $this->request->getPost('doSave') ) {

            $time = gmdate('Y-m-d H:i:s');

            $user = $this->users->find($id);

            $this->users->save([
				'id'       => $user->id,
				'acronym'  => $this->request->getPost('acronym'),
				'email'    => $this->request->getPost('email'),
				'name'     => $this->request->getPost('name'),
				'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
				'updated'  => $time,
				'active'   => $time
            ]);

            $url = $this->url->create('users/id/' . $id);
            $this->response->redirect($url);

        } 
        else {

        	$user = $this->users->find($id);

	        $this->di->dispatcher->forward([
	            'controller' => 'c-form',
	            'action'     => 'index',
	            'params'     => ['\Anax\Form\CFormEditUser', $user, 'Update user', '', 'users', 'posts']
	        ]);
        }

        $user = $this->session->get('user');

        $this->views->add('users/user', ['sUser' => $this->session->get('user')], 'user');
        $user['name'] === 'admin' ? $this->views->add('users/menu', [], 'posts') : false;
    }



	/**
	 * 	Delete a user
	 *
	 * 	@param int $id of the user to delete
	 *
	 *  @return void
	 */
	public function deleteAction($id = null)
	{
		if ( !isset($id) ) {
			die('Missing id');
		}

		$result = $this->users->delete($id);

		$url = $this->url->create('users/list');
		$this->response->redirect($url);
	}



	/**
	 * 	Delete (soft) user
	 *
	 *  @param int id of user to delete
	 *
	 *  @return void
	 */
	public function softDeleteAction($id = null)
	{
		if ( !isset($id) ) {
			die('Missing id');
		}

		$time = gmdate('Y-m-d H:i:s');

		$user = $this->users->find($id);

		$user->deleted = $time;
		$user->save();

		$url = $this->url->create('users/list');
		$this->response->redirect($url);
	}



	/**
	 *	List all active and not deleted users
	 *
	 * 	@return void
	 */
	public function activeAction()
	{
		$active = $this->session->get('user');
		$active['name'] != 'admin' ? $this->response->redirect($this->url->create('')) : null;

		$all = $this->users->query()
				->where('active is NOT NULL')
				->andWhere('deleted is NULL')
				->execute();

		$this->theme->setTitle('Active users');

		$this->views->add('users/list-all', ['users' => $all, 'title' => 'Active users'], 'posts');
		$this->views->add('users/user', ['sUser' => $this->session->get('user')], 'user');
		$this->views->add('users/menu', [], 'posts');
	}



	/**
	 *	List all inactive and not deleted users
	 *
	 * 	@return void
	 */
	public function inactiveAction()
	{
		$active = $this->session->get('user');
		$active['name'] != 'admin' ? $this->response->redirect($this->url->create('')) : null;

		$all = $this->users->query()
				->where('active is NULL')
				->andWhere('deleted is NULL')
				->execute();

		$this->theme->setTitle('Inactive users');

		$this->views->add('users/list-all', ['users' => $all, 'title' => 'Inactive users'], 'posts');
		$this->views->add('users/user', ['sUser' => $this->session->get('user')], 'user');
		$this->views->add('users/menu', [], 'posts');
	}



	/**
	 *	Activate a user
	 *	
	 * 	@param int id of user to activate
	 */
	public function activateAction($id = null)
	{
		$time = gmdate('Y-m-d H:i:s');

		$this->users->save(['id' => $id, 'active' => $time]);

		$url = $this->url->create('users/list');
		$this->response->redirect($url);
	}



	/**
	 *	Deactivate a user
	 *	
	 * 	@param int id of user to deactivate
	 */
	public function deactivateAction($id = null)
	{
		$this->users->save(['id' => $id, 'active' => null]);

		$url = $this->url->create('users/list');
		$this->response->redirect($url);
	}



	/**
	 *	Activate a user
	 *	
	 * 	@param int id of user to activate
	 */
	public function regretAction($id = null)
	{
		$this->users->save(['id' => $id, 'deleted' => null]);

		$url = $this->url->create('users/trash');
		$this->response->redirect($url);
	}



	/**
	 *	List all soft-deleted rows
	 *
	 *	@return void
	 */
	public function trashAction()
	{
		$active = $this->session->get('user');
		$active['name'] != 'admin' ? $this->response->redirect($this->url->create('')) : null;
		
		$all = $this->users->query()
				->where('deleted is NOT NULL')
				->execute();

		$this->theme->setTitle('Garbage bin');

		$this->views->add('users/trash', ['users' => $all, 'title' => 'Users in the garbage bin'], 'posts');
		$this->views->add('users/user', ['sUser' => $this->session->get('user')], 'user');
		$this->views->add('users/menu', [], 'posts');
	}



	/**
	 *	Empty the trashcan (delete all rows in trashcan)
	 *
	 * 	@return void
	 */
	public function emptyAction()
	{
		$result = $this->users->query()
				->where('deleted is NOT NULL')
				->execute();

		if ( $result ) {
			foreach ($result as $row) {
				$this->users->delete($row->id);
			}
		}

		$url = $this->url->create('users/list');
		$this->response->redirect($url);
	}



	/**
	 * Fetch all answers and find out which user that has created the most answers
	 * 
	 * @return void
	 */
	public function getTopUsersAnswersAction()
	{
		$data = $this->answers->findMostActiveUser(3);

		$this->views->add('main/list', ['title' => 'Most created answers', 'items' => $data], 'box-3');
	}



	/**
	 * Fetch all questions and find out which user that has created the most questions
	 * 
	 * @return void
	 */
	public function getTopUsersQuestionsAction()
	{
		$data = $this->questions->findMostActiveUser(3);

		$this->views->add('main/list', ['title' => 'Most created posts', 'items' => $data], 'box-4');
	}




	/**
	 * Is user logged in
	 *
	 * @return void
	 */
	public function isLoggedInAction()
	{
		$user = $this->session->get('user', false);
		return $user ? true : false;
	}



	/**
	 * Verify credentials and login user
	 *
	 * @param string $acronym which user to login
	 * 
	 * @return void
	 */
	public function loginAction($acronym = null)
	{
		$user = $this->users->findUserByAcronym($acronym);

		if ( isset($user->acronym) ) {
			if ( password_verify($this->request->getPost('password'), $user->password) ) {
				if ( $user->active === null ) {
					$this->fmsg->error('User is not active.');
					$this->response->redirect($this->url->create('login'));
				}
				else {
					$this->session->set('user', ['id' => $user->id, 'name' => $user->acronym, 'status' => 'loggedIn']);
					$this->response->redirect($this->url->create(''));
				}
			}
			else {
				$this->fmsg->error('Wrong password. Please try again.');
				$this->response->redirect($this->url->create('login'));
			}
		} 
		else {
			$this->fmsg->error('User not found.');
			$this->response->redirect($this->url->create('login'));
		}
	}



	/**
	 * Logout user
	 *
	 * @return void
	 */
	public function logoutAction()
	{
		$this->session->delete('user');
		$this->response->redirect($this->url->create(''));
	}
}