<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\party\PartyFactory;

class YourPartyForm extends PartySimpleForm {

    public function onCreation(): void {
        $this->setTitle("Your Party");
        $this->setContent("What do you want to check?");
        $this->addButton("Members");
        if($this->getSession()->isPartyLeader()) {
            $this->addButton("Disband the party");
            $this->addButton("Party Options");
        } else {
            $this->addButton("Leave the party");
        }
    }

    public function setCallback(?int $result): void {
        $session = $this->getSession();
        if($result === null or !$session->hasParty()) return;

        $player = $session->getPlayer();
        $party = $session->getParty();

        switch($result) {
            case 0:
                $player->sendForm(new PartyMembersForm($session));
                break;
            case 1:
                if($session->isPartyLeader()) {
                    foreach($party->getMembers() as $member) {
                        $party->remove($member);
                        PartyFactory::removeParty($party);
                    }
                } else {
                    $party->remove($session);
                }
                break;
            case 2:
                $player->sendForm(new PartyOptionsForm($session));
                break;
        }
    }

}