<?php

namespace Anax\Questions;

/**
 *	Model for storing comments data.
 * 
 */
class Questions extends \Anax\MVC\CDatabaseModel
{



	/**
	 * A method to find all questions
	 * 
	 * @return array with all questions
	 */
	public function findQuestions()
	{
		$this->db->select()
				->from($this->getSource())
				->orderby('created desc');
		
		$this->db->execute();
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}




	/**
	 * A method to find most active users 
	 *
	 * @param int $limit how many items to fetch
	 * 
	 * @return array
	 */
	public function findMostActiveUser($limit = 10)
	{
		$this->db->select(
			"
				*, count(user_id) as no
					from " . $this->getSource() . "
					group by user_id
					order by count(*) desc
					limit $limit
			"
		);
		
		$this->db->execute();
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}



	/**
	 * Find questions related to a specific tag
	 *
	 * @param string $tag which tag to fetch questions from
	 * 
	 * @return array with all questions
	 */
	public function findQuestionsRelatedToTag($tag)
	{
		$this->db->select(
			"
				* from " . $this->getSource() . "
				where tags like ?
				order by created desc
			"
		);

		$this->db->execute(['%' . $tag . '%']);
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}



	/**
	 * Find questions related to a specific tag
	 *
	 * @param string $tag which tag to fetch questions from
	 * 
	 * @return array with all questions
	 */
	public function findNoOfQuestionsRelatedToTag($tag)
	{
		$this->db->select(
			"
				count(tags) as no from questions
				where tags like ?
			"
		);

		$this->db->execute(['%' . $tag . '%']);
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}



	/**
	 * Find questions related to a specific user
	 *
	 * @param int $user which user to fetch questions from
	 * 
	 * @return array with all questions
	 */
	public function findQuestionsRelatedToUser($user)
	{
		$this->db->select(
			"
				* from " . $this->getSource() . "
				where user_id like ?
				order by created desc
			"
		);

		$this->db->execute([$user]);
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}



	/**
	 * Find questions based on rating
	 *
	 * @param int $limit how many items to fetch
	 * 
	 * @return array with questions
	 */
	public function findLatestQuestions($limit = 5)
	{
		$this->db->select(
			"
				* from " . $this->getSource() . "
				order by created desc
				limit $limit
			"
		);

		$this->db->execute();
		$this->db->setFetchModeClass(__CLASS__);

		return $this->db->fetchAll();
	}
}