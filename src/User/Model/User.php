<?php

declare(strict_types=1);

namespace GC\User\Model;

/**
 * @Table(name="user")
 * @Entity(repositoryClass="GC\User\Model\UserRepository")
 */
class User
{
    /**
     * @var int
     * @Column(name="user_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var string
     * @Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @Column(name="mail", type="string", length=150, nullable=false)
     */
    private $mail;

    /**
     * @var string
     * @Column(name="password", type="string", length=70, nullable=false)
     */
    private $password;

    /**
     * @param string $name
     * @param string $mail
     * @param string $password
     */
	public function __construct(string $name, string $mail, string $password)
    {
		$this->name = $name;
		$this->mail = $mail;
        $this->password = $password;
	}
	
	/**
	 * @return int
	 */
	public function getUserId(): int
    {
		return $this->userId;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string
    {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
    {
		return $this->password;
	}
	
	/**
	 * @return string
	 */
	public function getMail(): string
    {
		return $this->mail;
	}
}