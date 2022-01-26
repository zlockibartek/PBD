<?php

namespace Home\DBManager\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Page")
 */

 class Page
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

    /**
	 * @ORM\Column(type="string")
	 */
	private $content;


    /**
	 * @ORM\Column(type="string")
	 */
	private $email;

    /**
    * @ORM\Column(type="datetime", nullable=false)
    * @ORM\Version
	 */
    private $createTime;


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

	/**
	 * Get the value of title
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Set the value of title
	 */
	public function setTitle($title): self
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Get the value of content
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Set the value of content
	 */
	public function setContent($content): self
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * Get the value of email
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set the value of email
	 */
	public function setEmail($email): self
	{
		$this->email = $email;

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
     */
    public function setCreateTime($createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }
 }