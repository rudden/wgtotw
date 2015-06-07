<?php

namespace Anax\Answers;

/**
 *	Model for storing ansers data.
 * 
 */
class Answers extends \Anax\MVC\CDatabaseModel
{



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
	 * A method to find all answers related to a question
	 * 
	 * @param int $q_id which question to fetch related answers from
	 * @param string $order which order to order the answers by
	 * 
	 * @return array with all related answers
	 */
	public function findAnswers($q_id, $order = 'rating')
	{
		$this->db->select()
				->from($this->getSource())
				->where('question_id = ?')
				->andWhere('answer_id is null')
				->orderby($order . ' desc');
		
		$this->db->execute([$q_id]);
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}


	
	/**
	 * A method to find answer related to answer
	 * 
	 * @param int $a_id which answer to fetch related answers from
	 * 
	 * @return array
	 */
	public function findAnswer($a_id)
	{
		$this->db->select()
				->from($this->getSource())
				->where('answer_id = ?');
		
		$this->db->execute([$a_id]);
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}



	/**
	 * A method to find all answers related to a user
	 * 
	 * @param int $id which user to fetch related answers from
	 * 
	 * @return array with all related answers
	 */
	public function findAnswersRelatedToUser($id)
	{
		$this->db->select()
				->from($this->getSource())
				->where('user_id = ?')
				->andWhere('answer_id is null')
				->orderby('rating desc');
		
		$this->db->execute([$id]);

		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}



	/**
	 * A method to find all answers related to an answer
	 * 
	 * @param int $id which answer to fetch related answers from
	 * 
	 * @return array with all related answers
	 */
	public function findAnswersRelatedToAnswer($id)
	{
		$this->db->select()
				->from($this->getSource())
				->where('answer_id = ?')
				->andWhere('question_id is null')
				->orderby('rating desc');
		
		$this->db->execute([$id]);
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}



	/**
	 * [findAndCountAnswers description]
	 * @param  int $q_id which question to count related answers to
	 * @return int number of answers
	 */
	public function findAndCountAnswers($q_id)
	{
		$this->db->select(
			"
				count(question_id) as amount FROM " . $this->getSource() . " as A
				inner join questions as Q on Q.id = A.question_id
				WHERE Q.id = ?
			"
		);

		//$this->db->setVerbose();

		$this->db->execute([$q_id]);
		$this->db->setFetchModeClass(__CLASS__);

		return $this->db->fetchAll();
	}
}