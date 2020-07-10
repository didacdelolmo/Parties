<?php

declare(strict_types=1);


namespace diduhless\parties\party;


use diduhless\parties\session\Session;

class Invitation {

    public const INVITATION_LENGTH = 60;

    /** @var Session */
    private $sender;

    /** @var Session */
    private $target;

    /** @var string */
    private $partyId;

    public function __construct(Session $sender, Session $target, string $partyId) {
        $this->sender = $sender;
        $this->target = $target;
        $this->partyId = $partyId;
    }

    public function getSender(): Session {
        return $this->sender;
    }

    public function attemptToAccept(): void {
        $this->target->removeInvitation($this);
    }

}