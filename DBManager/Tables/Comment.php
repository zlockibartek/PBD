<?php

namespace Home\DBManager\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Comment")
 */

 class Comment
 {
    /**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
	 */
	private $id;

    /**
	 * @ORM\Column(type="string")
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="nickname")
	 */
	private $nickname;

    /**
	 * @ORM\Column(type="string")
	 */
	private $content;

    /**
	 * @ORM\Column(type="string", nullable="TRUE")
	 */
	private $attachments;

    /**
    * @ORM\Column(type="datetime", nullable=false)
    * @ORM\Version
	 */
    private $createTime;

    /**
	 * @ORM\Column(type="integer")
	 * @ORM\ManyToOne(targetEntity="Page", inversedBy="id")
	 */
	private $pageId;

    //???
    /**
	 * @ORM\Column(type="string")
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="email")
	 */
	private $userEmail;


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
	 * Get the value of nickname
	 */ 
	public function getNickname()
	{
		return $this->nickname;
	}

	/**
	 * Set the value of nickname
	 *
	 * @return  self
	 */ 
	public function setNickname($nickname)
	{
		$this->nickname = $nickname;

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
	 *
	 * @return  self
	 */ 
	public function setContent($content)
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * Get the value of attachments
	 */ 
	public function getAttachments()
	{
		return $this->attachments;
	}

	/**
	 * Set the value of attachments
	 *
	 * @return  self
	 */ 
	public function setAttachments($attachments)
	{
		$this->attachments = $attachments;

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
	 * Get the value of pageId
	 */ 
	public function getPageId()
	{
		return $this->pageId;
	}

	/**
	 * Set the value of pageId
	 *
	 * @return  self
	 */ 
	public function setPageId($pageId)
	{
		$this->pageId = $pageId;

		return $this;
	}

	/**
	 * Get the value of Usersemail
	 */ 
	public function getUsersemail()
	{
		return $this->Usersemail;
	}

	/**
	 * Set the value of Usersemail
	 *
	 * @return  self
	 */ 
	public function setUsersemail($Usersemail)
	{
		$this->Usersemail = $Usersemail;

		return $this;
	}

	/**
	 * Get the value of userEmail
	 */
	public function getUserEmail()
	{
		return $this->userEmail;
	}

	/**
	 * Set the value of userEmail
	 */
	public function setUserEmail($userEmail): self
	{
		$this->userEmail = $userEmail;

		return $this;
	}
 }