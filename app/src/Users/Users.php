<?php

namespace Anax\Users;

/**
 *	Model for storing user data.
 * 
 */
class Users extends \Anax\MVC\CDatabaseModel
{



	/**
	 * Find all users
	 * 
	 * @return void
	 */
	public function findAllUsers()
	{
		$this->db->select()
				->from($this->getSource());

		$this->db->execute();
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}




	/**
	 * A method to find user connected to a question
	 * 
	 * @param int $id which user to find
	 * 
	 * @return array
	 */
	public function findUserById($id)
	{
		$this->db->select()
				->from($this->getSource())
				->where('id = ?');
		
		$this->db->execute([$id]);
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}



	/**
	 * A method to find user
	 * 
	 * @param string $acronym which user to find
	 * 
	 * @return array
	 */
	public function findUserByAcronym($acronym)
	{
		$this->db->select()
				->from($this->getSource())
				->where('acronym = ?');
		
		$this->db->execute([$acronym]);
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchInto($this);
	}
}