<?php

namespace Home\DBManager\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */

 class User
 {
    /**
     * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	private $nickname;

    /**
     * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	private $email;


    /**
	 * @ORM\Column(type="string")
	 */
	private $password;

    /**
	 * @ORM\Column(type="string")
	 */
	private $name;

    /**
	 * @ORM\Column(type="string")
	 */
	private $role;


    //
	//	Getters&Setters
	//


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
	 * Get the value of email
	 */ 
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set the value of email
	 *
	 * @return  self
	 */ 
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * Get the value of password
	 */ 
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set the value of password
	 *
	 * @return  self
	 */ 
	public function setPassword($password)
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Get the value of name
	 */ 
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the value of name
	 *
	 * @return  self
	 */ 
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get the value of role
	 */ 
	public function getRole()
	{
		return $this->role;
	}

	/**
	 * Set the value of role
	 *
	 * @return  self
	 */ 
	public function setRole($role)
	{
		$this->role = $role;

		return $this;
	}
 }