<?php

namespace Home\DBManager\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Contains")
 */

 class Contains
 {
    /**
     * @ORM\Id
	 * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="id")
	 */
	private $categoryId;

    /**
     * @ORM\Id
	 * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="id")
	 */
	private $pageId;



	/**
	 * Get the value of categoryId
	 */
	public function getCategoryId()
	{
		return $this->categoryId;
	}

	/**
	 * Set the value of categoryId
	 */
	public function setCategoryId($categoryId): self
	{
		$this->categoryId = $categoryId;

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
	 */
	public function setPageId($pageId): self
	{
		$this->pageId = $pageId;

		return $this;
	}
 }