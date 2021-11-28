<?php

namespace Home\Collections;

class User 
{
	const MODERATOR = 'moderator';
	const USER = 'user';
	private ?array $roles;
	private ?int $id;

	public function __construct($id = null, $roles = array())
	{
		$this->id = $id;
		$this->roles = $roles;
	}
	
	public function isModerator() {
		return in_array(self::MODERATOR, $this->roles);
	}

	public function isUser() {
		return in_array(self::USER, $this->roles);
	}

	public function getRoles(): ?array
	{
		return $this->roles;
	}

	public function getId(): ?int
	{
		return $this->id;
	}
}