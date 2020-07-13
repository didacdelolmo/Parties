<?php


namespace diduhless\parties\event;


use diduhless\parties\party\Party;
use diduhless\parties\session\Session;

class PartyLeaderPromoteEvent extends PartyEvent {

    /** @var Session */
    private $newLeader;

    public function __construct(Party $party, Session $session, Session $newLeader) {
        $this->newLeader = $newLeader;
        parent::__construct($party, $session);
    }

    public function getNewLeader(): Session {
        return $this->newLeader;
    }

}