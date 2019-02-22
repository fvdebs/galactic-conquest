<?php

declare(strict_types=1);

namespace GC\User\Model;

/**
 * @Entity
 * @Table(name="user")
 */
class User
{
	/**
	 * @var int
	 * @Id
	 * @Column(type="bigint", name="user_id", nullable=FALSE, length=20)
	 * @GeneratedValue
	 */
	private $userId;
	
	/**
	 * @var string
	 * @Column(type="string", name="name", nullable=FALSE, length=30)
	 */
	private $name;
	
	/**
	 * @var string
	 * @Column(type="string", name="mail", nullable=FALSE, length=150)
	 */
	private $mail;

    /**
     * @var string
     * @Column(type="string", name="password", nullable=FALSE, length=50)
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
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

	/**
	 * @return string
	 */
	public function getPassword(): string
    {
		return $this->password;
	}
	
	/**
	 * @param string $password
     *
	 * @return void
	 */
	public function setPassword($password): void
    {
		$this->password = $password;
	}
	
	/**
	 * @return string
	 */
	public function getMail(): string
    {
		return $this->mail;
	}
	
	/**
	 * @param string $mail
     *
	 * @return void
	 */
	public function setMail($mail): void
    {
		$this->mail = $mail;
	}
}
