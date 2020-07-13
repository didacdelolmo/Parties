<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\event\PartyDisbandEvent;
use diduhless\parties\event\PartyLeaveEvent;
use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\party\PartyFactory;

class YourPartyForm extends PartySimpleForm {

    public function onCreation(): void {
        $this->setTitle("Your Party");
        $this->setContent("What do you want to check?");
        $this->addButton("Members");
        if($this->getSession()->isPartyLeader()) {
            $this->addButton("Invite a player");
            $this->addButton("Party Options");
            $this->addButton("Disband the party");
        } else {
            $this->addButton("Leave the party");
        }
    }

    public function setCallback(?int $result): void {
        $session = $this->getSession();
        if($result === null or !$session->hasParty()) return;
        $player = $session->getPlayer();

        switch($result) {
            case 0:
                $player->sendForm(new PartyMembersForm($session));
                break;
            case 1:
                if($session->isPartyLeader()) {
                    $player->sendForm(new PartyInviteForm($session));
                } else {
                    $this->leaveParty();
                }
                break;
            case 2:
                $player->sendForm(new PartyOptionsForm($session));
                break;
            case 3:
                $this->disbandParty();
        }
    }

    private function disbandParty(): void {
        $session = $this->getSession();
        $party = $session->getParty();

        $event = new PartyDisbandEvent($party, $session);
        $event->call();
        if($event->isCancelled()) return;

        foreach($party->getMembers() as $member) {
            $party->remove($member);
            PartyFactory::removeParty($party);
        }
    }

    private function leaveParty(): void {
        $session = $this->getSession();
        $party = $session->getParty();

        $event = new PartyLeaveEvent($party, $session);
        $event->call();
        if(!$event->isCancelled()) {
            $party->remove($session);
        }
    }

}