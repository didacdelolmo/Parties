<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\event\PartyLeaderPromoteEvent;
use diduhless\parties\event\PartyMemberKickEvent;
use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\session\Session;
use diduhless\parties\session\SessionFactory;

class PartyMemberForm extends PartySimpleForm {

    /** @var Session */
    private $member;

    public function __construct(Session $member, Session $session) {
        $this->member = $member;
        parent::__construct($session);
    }

    public function onCreation(): void {
        $this->setTitle("Party Member");
        $this->setContent("What do you want to do with this member?");
        $this->addButton("Kick him from the party");
        $this->addButton("Promote to party leader");
    }

    public function setCallback(?int $result): void {
        $session = $this->getSession();
        if($result === null or !$session->isPartyLeader() or SessionFactory::getSession($this->member->getPlayer()) === null) return;

        switch($result) {
            case 0:
                $this->onKick();
                break;
            case 1:
                $this->onPromote();
                break;
        }
    }

    private function onKick(): void {
        $session = $this->getSession();
        $party = $session->getParty();

        $event = new PartyMemberKickEvent($party, $session);
        $event->call();

        if(!$event->isCancelled()) {
            $party->remove($this->member);
        }
    }

    private function onPromote(): void {
        $session = $this->getSession();
        $party = $session->getParty();

        $event = new PartyLeaderPromoteEvent($party, $session);
        $event->call();

        if(!$event->isCancelled()) {
            $party->setLeader($this->member);
        }
    }
}