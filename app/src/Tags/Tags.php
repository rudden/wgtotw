<?php

namespace Anax\Tags;

/**
 *	Model for storing tags data.
 * 
 */
class Tags extends \Anax\MVC\CDatabaseModel
{



	/**
	 * Find tagdata based on tag name
	 * 
	 * @param  string $tag which tag to fetch data from
	 * 
	 * @return array with tag data
	 */
	public function findTagFromName($tag)
	{
		$this->db->select(
			"
				* from tags
				where tag like ?
			"
		);

		$this->db->execute(['%' . $tag . '%']);
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}



	/**
	 * Find all tags from the questions table
	 * 
	 * @return array
	 */
	public function findTags()
	{
		$this->db->select(
			"
				tags from questions
			"
		);

		$this->db->execute();
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}




	/**
	 * Find all tags
	 * 
	 * @return array with all tags
	 */
	public function returnTagsAsObject()
	{
		$this->db->select()
				->from($this->getSource());

		$this->db->execute();
		$this->db->setFetchModeClass(__CLASS__);
		
		return $this->db->fetchAll();
	}




	/**
	 * A method to find all tags
	 * 
	 * @return array with all tags
	 */
	public function returnTagsAsArray()
	{
		$this->db->select(
			"
				tag from " . $this->getSource() . "
				where deleted is null
			"
		);
		
		$this->db->execute();
		$this->db->setFetchModeClass(__CLASS__);
		
		$tags = $this->db->fetchAll();

		foreach ($tags as $tag) {
			$taggar[] = $tag->tag;
		}

		return $taggar;
	}
}