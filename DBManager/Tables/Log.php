<?php

namespace Home\DBManager\Tables;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="Log")
 */

 class Log
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
	private $action;

    /**
	 * @ORM\Column(type="string")
	 */
	private $message;

    /**
    * @ORM\Column(type="datetime", nullable=false)
    * @ORM\Version
	 */

    private $createTime;



	//
	//	Getters&Setters
	//


	/**
	 * Get the value of action
	 */ 
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * Set the value of action
	 *
	 * @return  self
	 */ 
	public function setAction($action)
	{
		$this->action = $action;

		return $this;
	}

	/**
	 * Get the value of message
	 */ 
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Set the value of message
	 *
	 * @return  self
	 */ 
	public function setMessage($message)
	{
		$this->message = $message;

		return $this;
	}

    /**
     * Get the value of createTime
     */ 
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set the value of createTime
     *
     * @return  self
     */ 
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

	/**
	 * Get the value of id
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set the value of id
	 */
	public function setId($id): self
	{
		$this->id = $id;

		return $this;
	}
 }