<?php

declare(strict_types=1);


namespace diduhless\parties\party;


use diduhless\parties\session\Session;

class Party {

    /** @var string */
    private $id;

    /** @var Session */
    private $leader;

    /** @var Session[] */
    private $members = [];

    /** @var bool */
    private $locked = false;

    /** @var null|int */
    private $slots = null;

    public function __construct(string $id, Session $leader) {
        $this->id = $id;
        $this->leader = $leader;
        $this->members[] = $leader;
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

    public function setLeader(Session $leader): void {
        $this->leader = $leader;
    }

    public function setLocked(bool $locked): void {
        $this->locked = $locked;
    }

    public function setSlots(?int $slots): void {
        $this->slots = $slots;
    }

    public function add(Session $session): void {
        if(!in_array($session, $this->members)) {
            $this->members[] = $session;
        }
        $session->setParty($this);
    }

    public function remove(Session $session): void {
        if(in_array($session, $this->members)) {
            unset($this->members[array_search($session, $this->members)]);
        }
        $session->setParty(null);
    }

}