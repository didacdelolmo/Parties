<?php

declare(strict_types=1);


namespace diduhless\parties\party;


use diduhless\parties\event\PartyJoinEvent;
use diduhless\parties\session\Session;

class Invitation {

    public const INVITATION_LENGTH = 60;

    private Session $sender;
    private Session $target;

    private string $partyId;

    public function __construct(Session $sender, Session $target, string $partyId) {
        $this->sender = $sender;
        $this->target = $target;
        $this->partyId = $partyId;
    }

    public function getSender(): Session {
        return $this->sender;
    }

    public function getTarget(): Session {
        return $this->target;
    }

    public function getPartyId(): string {
        return $this->partyId;
    }

    public function attemptToAccept(): void {
        $this->target->removeInvitation($this);

        if($this->target->hasParty()) {
            $this->target->message("{RED}You cannot be in a party to do this!");
            return;
        } elseif(!$this->sender->isOnline()) {
            $this->target->message("{RED}You could not join the party because the party leader is not online!");
            return;
        }
        $party = $this->sender->getParty();
        if($party === null or $party->getId() !== $this->partyId) {
            $this->target->message("{RED}You could not join the party because the party leader has left the party!");
            return;
        } elseif($party->isFull()) {
            $this->target->message("{RED}You could not join this party because it is full!");
        }

        $event = new PartyJoinEvent($party, $this->target);
        $event->call();
        if(!$event->isCancelled()) {
            $party->add($this->target);
            $this->target->removeInvitationsFromParty($party);
        }
    }

}