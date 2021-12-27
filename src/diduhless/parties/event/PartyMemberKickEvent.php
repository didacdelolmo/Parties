<?php


namespace diduhless\parties\event;


use diduhless\parties\party\Party;
use diduhless\parties\session\Session;

class PartyMemberKickEvent extends PartyEvent {

    private Session $member;

    public function __construct(Party $party, Session $session, Session $member) {
        $this->member = $member;
        parent::__construct($party, $session);
    }

    public function getMember(): Session {
        return $this->member;
    }

}