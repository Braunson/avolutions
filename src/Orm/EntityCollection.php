<?php
/**
 * AVOLUTIONS
 * 
 * Just another open source PHP framework.
 * 
 * @copyright	Copyright (c) 2019 - 2020 AVOLUTIONS
 * @license		MIT License (http://avolutions.org/license)
 * @link		http://avolutions.org
 */
 
namespace Avolutions\Orm;

use Avolutions\Collection\CollectionInterface;
use Avolutions\Collection\CollectionTrait;
use Avolutions\Database\Database;
use Avolutions\Di\Container;
use Avolutions\Logging\Logger;

/**
 * EntityCollection class
 *
 * An EntityCollection contains all elements of a specific Entity. 
 * It provides the methods for filtering and sorting these elements.
 *
 * @author	Alexander Vogt <alexander.vogt@avolutions.org>
 * @since	0.1.0
 */
class EntityCollection implements CollectionInterface
{
    use CollectionTrait;

	/**
	 * @var string $entity The name of the entity.
	 */
	private $entity;
	
	/**
	 * @var string $EntityConfiguration The configuration of the entity.
	 */
	private $EntityConfiguration;

	/**
	 * @var string $EntityMapping The mapping of the entity.
	 */
	private $EntityMapping;

	/**
	 * @var string $limitClause The limit clause for the query.
	 */
	private $limitClause;

	/**
	 * @var string $orderByClause The orderBy clause for the query.
	 */
	private $orderByClause;

	/**
	 * @var string $whereClause The where clause for the query.
	 */
	private $whereClause;
	
	/**
	 * __construct
	 * 
	 * Creates a new EntityCollection for the given Entity type and loads the corresponding 
	 * EntityConfiguration and EntityMapping.
	 * 
	 * @param string $entity The name of the Entity type.
	 */
    public function __construct($entity)
    {
		$this->entity = $entity;
		
		$this->EntityConfiguration = new EntityConfiguration($this->entity);
		$this->EntityMapping = $this->EntityConfiguration->getMapping();
    }	
    
    /**
	 * count
	 * 
	 * Returns the number of items in the Collection.
	 * 
	 * @return int The number of items in the Collection.
	 */
    public function count()
    {
		$this->execute();

        return count($this->items);
    }

	/**
	 * execute
	 * 
	 * Executes the previously created database query and loads the Entites from
	 * the database to the Entities property.
	 */
    private function execute()
    {
        $Container = Container::getInstance();
		$Database = $Container->get('Avolutions\Database\Database');

		$query = 'SELECT ';
		$query .= $this->EntityConfiguration->getFieldQuery();
		$query .= ' FROM ';
        $query .= '`'.$this->EntityConfiguration->getTable().'`';
        $query .= $this->getJoinStatement();
		$query .= $this->getWhereClause();
		$query .= $this->getOrderByClause();
		$query .= $this->getLimitClause();
		
		$stmt = $Database->prepare($query);
		
		Logger::debug($query);  

		$stmt->execute();

		while ($row = $stmt->fetch($Database::FETCH_ASSOC)) {     
            $entityValues = [];

            foreach($row AS $columnKey => $columnValue) {
                $explodedKey = explode('.', $columnKey);
                $entityName = $explodedKey[0];
                $columnName = $explodedKey[1];

                if($entityName == $this->entity) {
                    $entityValues[$columnName] = $columnValue;
                } else {
                    $entityValues[$entityName][$columnName] = $columnValue;
                }
            }

            $fullEntityName = APP_MODEL_NAMESPACE.$this->entity;
            $Entity = new $fullEntityName($entityValues);

            $this->items[] = $Entity;
		}
	}	

	/**
	 * limit
	 * 
	 * Sets the number of records that should be loaded from the database.
	 * 
	 * @param int $rowCount The number of records that should be loaded from the database.
	 * @param int $offset Specifies the offset of the first row to return.
	 * 
	 * @return EntityCollection $this
	 */
    public function limit($rowCount, $offset = 0)
    {
		$this->limitClause = $rowCount;
		if ($offset > 0) {
			$this->limitClause .= ' OFFSET '.$offset;
		}

		return $this;
	}
		
	/**
	 * getAll
	 * 
	 * Returns all previously loaded Entities of the EntityCollection.
	 * 
	 * @return array All previously loaded Entities.
	 */
    public function getAll()
    {
		$this->execute();

		return $this->items;
	}
	
	/**
	 * getById
	 * 
	 * Returns the matching Entity for the given id.
	 * 
	 * @param int $id The identifier of the Entity.
	 * 
	 * @return Entity The matching Entity for the given id.
	 */
    public function getById($id)
    {
		$this->where($this->EntityConfiguration->getIdColumn().' = '.$id);
		$this->execute();

		return $this->items[0];
	}

	/**
	 * getFirst
	 * 
	 * Returns the first Entity of the EntityCollection.
	 * 
	 * @return Entity The first Entity of the EntityCollection.
	 */
    public function getFirst()
    {
		$this->limit(1)->execute();

		return $this->items[0] ?? null;
    }
    
    /**
	 * getJoinStatement
	 * 
	 * Returns a join statement if the Entity has a joined Entity defined in the EntityMapping.
     * 
     * @return string The join statement
	 */
    private function getJoinStatement()
    {
        $joinStmt = '';

        // Check all properties from the EntityMapping
        foreach ($this->EntityMapping as $key => $value) {
            // If the property is of type Entity
            if ($value['isEntity']) {
                // Load the configuration of the linked Entity
                $EntityConfiguration = new EntityConfiguration($value['type']);
                
                // Create the JOIN statement:
                // " JOIN {JoinedTable} ON {Table}.{Column} = {JoinedTable}.{JoinedColumn}"
                $joinStmt .= ' JOIN ';
                $joinStmt .= '`'.$EntityConfiguration->getTable().'`';
                $joinStmt .= ' ON ';
                $joinStmt .= $this->EntityConfiguration->getTable().'.'.$value['column'];
                $joinStmt .= ' = ';
                $joinStmt .= $EntityConfiguration->getTable().'.'.$EntityConfiguration->getIdColumn();
            }
        }

		return $joinStmt;
	}

	/**
	 * getLast
	 * 
	 * Returns the last Entity of the EntityCollection.
	 * 
	 * @return Entity The last Entity of the EntityCollection.
	 */
    public function getLast()
    {
		$this->execute();

		return end($this->items);
	}

	/**
	 * getLimitClause
	 * 
	 * Returns the processed limit clause for the final query. 
	 * 
	 * @return string The processed limit clause.
	 */
    private function getLimitClause()
    {
		if (strlen($this->limitClause) > 0) {
			return ' LIMIT '.$this->limitClause;
		}

		return '';
	}

	/**
	 * getOrderByClause
	 * 
	 * Returns the processed orderBy clause for the final query. 
	 * 
	 * @return string The processed orderBy clause.
	 */
    private function getOrderByClause()
    {
		if (strlen($this->orderByClause) > 0) {
			return ' ORDER BY '.rtrim($this->orderByClause, ', ');
		}

		return '';
	}

	/**
	 * getWhereClause
	 * 
	 * Returns the processed where clause for the final query. 
	 * 
	 * @return string The processed where clause.
	 */
    private function getWhereClause()
    {
		if (strlen($this->whereClause) > 0) {
			return ' WHERE '.$this->whereClause;
		}

		return '';
	}

	/**
	 * orderBy
	 * 
	 * Sets the sorting of the records that should be loaded from the database.
	 * Can be called multiple times to sort on multiple columns.
	 * 
	 * @param string $field The name of the Entity property to sort by.
	 * @param bool $descending Whether the sort order should be descending or not.
	 * 
	 * @return EntityCollection $this
	 */
    public function orderBy($field, $descending = false)
    {
		$this->orderByClause .= $this->EntityMapping->$field['column'];
		if ($descending) {
			$this->orderByClause .= ' DESC';
		}
		$this->orderByClause .= ', ';

		return $this;
	}	

	/**
	 * where
	 * 
	 * Filters the EntityCollection by the given condition.
	 * 
	 * @param string $condition The filter condition.
	 * 
	 * @return EntityCollection $this
	 */
    public function where($condition)
    {
		$this->whereClause .= $condition;

		return $this;
	}
}