<?php

declare(strict_types=1);

namespace GC\User\Model;

use Doctrine\Common\Collections\ArrayCollection;
use GC\Universe\Model\Universe;

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
     * @var \GC\Player\Model\Player[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Player\Model\Player", mappedBy="user", fetch="EXTRA_LAZY", cascade={"all"}, orphanRemoval=true)
     */
    private $players;

    /**
     * @param string $name
     * @param string $mail
     * @param string $password
     */
	public function __construct(string $name, string $mail, string $password)
    {
        $this->players = new ArrayCollection();
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
     * @return \GC\Player\Model\Player[]
     */
	public function getPlayers(): array
    {
	    return $this->players->getValues();
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return bool
     */
    public function hasPlayerIn(Universe $universe): bool
    {
        foreach ($this->getPlayers() as $player) {
            if ($player->getUniverse()->getUniverseId() === $universe->getUniverseId()) {
                return true;
            }
        }

        return false;
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