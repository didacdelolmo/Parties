<?php

declare(strict_types=1);


namespace diduhless\parties\party;


use diduhless\parties\session\Session;

class Party {

    /** @var string */
    private $id;

    /** @var Session[] */
    private $members;

    /** @var Session */
    private $leader;

    /** @var bool */
    private $locked = false;

    /** @var null|int */
    private $slots = null;

    /**
     * Party constructor.
     * @param string $id
     * @param Session[] $members
     * @param Session $leader
     */
    public function __construct(string $id, array $members, Session $leader) {
        $this->id = $id;
        $this->members = $members;
        $this->leader = $leader;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getMembers(): array {
        return $this->members;
    }

    public function getLeader(): Session {
        return $this->leader;
    }

    public function getLeaderName(): string {
        return $this->leader->getPlayer()->getName();
    }

    public function isLocked(): bool {
        return $this->locked;
    }

    public function setLocked(bool $locked): void {
        $this->locked = $locked;
    }

    public function setSlots(?int $slots): void {
        $this->slots = $slots;
    }

}