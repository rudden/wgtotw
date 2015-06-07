<?php

namespace Anax\MVC;

/**
 *	Model for Databases.
 * 
 */
class CDatabaseModel implements \Anax\DI\IInjectionAware
{
	use \Anax\DI\TInjectable;



	/**
	 *	Find and return all
	 *	
	 * 	@return array
	 */
	public function findAll()
	{
		$this->db->select()
				->from($this->getSource())
				->where('deleted is NULL');
		
		$this->db->execute();
		$this->db->setFetchModeClass(__CLASS__);
		return $this->db->fetchAll();
	}



	/**
	 *	Find and return specific user
	 *
	 *	@return this
	 */
	public function find($id)
	{
		$this->db->select()
				->from($this->getSource())
				->where('id = ?');

		$this->db->execute([$id]);
		return $this->db->fetchInto($this);
	}



	/**
	 *	Save the current object/row
	 *	
	 * 	@param array $values key/valeus to save or empty to use object properties
	 * 	
	 * 	@return boolean true/false if saving went ok
	 */
	public function save($values = [])
	{
		$this->setProperties($values);
		$values = $this->getProperties();

		if (isset($values['id'])) {
			return $this->update($values);
		} else {
			return $this->create($values);
		}
	}



	/**
	 *	Create a new user
	 *
	 * 	@param array $values key/values to save
	 *
	 * 	@return boolean true/false if saving went ok
	 */
	public function create($values)
	{
		$keys   = array_keys($values);
		$values = array_values($values);

		$this->db->insert(
			$this->getSource(),
			$keys
		);

		$result = $this->db->execute($values);

		$this->id = $this->db->lastInsertId();

		return $result;
	}



	/**
	 *	Update existing row
	 *
	 * 	@param array $values key/values to save
	 *
	 * 	@return boolean true/false if saving went ok
	 */
	public function update($values)
	{
		$keys   = array_keys($values);
		$values = array_values($values);

		// Remove id and use it as a where-clause
		unset($keys['id']);
		$values[] = $this->id;

		$this->db->update(
			$this->getSource(),
			$keys,
			'id = ?'
		);

		return $this->db->execute($values);
	}



	/**
	 * 	Delete an existing row
	 *
	 * 	@param int $id of row to delete
	 *
	 *  @return boolean true/false if saving went ok
	 */
	public function delete($id)
	{
		$this->db->delete(
			$this->getSource(),
			'id = ?'
		);

		return $this->db->execute([$id]);
	}
	


	/**
	 * 	Build a select-query
	 *
	 * 	@param string $columns which columns to select
	 *
	 * 	@return $this
	 */
	public function query($columns = '*')
	{
		$this->db->select($columns)
				->from($this->getSource());

		return $this;
	}



	/**
	 * 	Build the where-part of the query
	 *
	 * 	@param string $condition for building the where-part of the query
	 *
	 * 	@return $this
	 */
	public function where($condition)
	{
		$this->db->where($condition);

		return $this;
	}



	/**
	 * 	Build the where-part of the query
	 *
	 * 	@param string $condition for building the where-part of the query
	 *
	 * 	@return $this
	 */
	public function andWhere($condition)
	{
		$this->db->andWhere($condition);

		return $this;
	}



	/**
	 * 	Execute the buildt query
	 *
	 * 	@param string $query custom query
	 *
	 * 	@return $this
	 */
	public function execute($params = [])
	{
		$this->db->execute($this->db->getSQL(), $params);
		$this->db->setFetchModeClass(__CLASS__);

		return $this->db->fetchAll();
	}



	/**
	 *	Fetch the table name
	 *
	 * 	@return string with table name
	 */
	public function getSource()
	{
		return strtolower(implode('', array_slice(explode('\\', get_class($this)), -1)));
	}



	/**
	 *	Get object properties
	 *	
	 * 	@return array with object properties
	 */
	public function getProperties()
	{
		$properties = get_object_vars($this);
		unset($properties['di']);
		unset($properties['db']);

		return $properties;
	}



	/**
	 *	Set object properties
	 *	
	 * 	@param array $properties with properties to set
	 * 	
	 * 	@return void
	 */
	public function setProperties($properties)
	{
		// Update existing object with new incoming values
		if (!empty($properties)) {
			foreach ($properties as $key => $value) {
				$this->$key = $value;
			}
		}
	}
}