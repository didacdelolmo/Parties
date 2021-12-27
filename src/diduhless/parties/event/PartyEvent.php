<?php


namespace diduhless\parties\event;


use diduhless\parties\party\Party;
use diduhless\parties\session\Session;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;

abstract class PartyEvent extends Event implements Cancellable {
    use CancellableTrait;

    private Party $party;
    private Session $session;

    public function __construct(Party $party, Session $session) {
        $this->party = $party;
        $this->session = $session;
    }

    public function getParty(): Party {
        return $this->party;
    }

    /*
     * Returns the session that executed the event
     */
    public function getSession(): Session {
        return $this->session;
    }

}