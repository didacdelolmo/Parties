<?php


namespace diduhless\parties\event;


use diduhless\parties\party\Party;
use diduhless\parties\session\Session;

class PartyLeaderPromoteEvent extends PartyEvent {

    private Session $new_leader;

    public function __construct(Party $party, Session $session, Session $newLeader) {
        $this->new_leader = $newLeader;
        parent::__construct($party, $session);
    }

    public function getNewLeader(): Session {
        return $this->new_leader;
    }

}