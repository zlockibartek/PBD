<?php

namespace Home\DBManager\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Category")
 */

 class Category
 {
    /**
     * @ORM\Id
	 * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
	 */
	private $id;

    /**
	 * @ORM\Column(type="string")
	 */
	private $title;


	//
	//	Getters&Setters
	//

    
	/**
	 * Get the value of id
	 */ 
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set the value of id
	 *
	 * @return  self
	 */ 
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Get the value of title
	 */ 
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Set the value of title
	 *
	 * @return  self
	 */ 
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}
 }