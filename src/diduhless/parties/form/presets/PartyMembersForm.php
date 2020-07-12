<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\session\Session;
use pocketmine\Player;

class PartyMembersForm extends PartySimpleForm {

    /** @var Session[] */
    private $members;

    public function onCreation(): void {
        $this->setTitle("Party Members");
        $this->setContent("Current members in your party:");

        $session = $this->getSession();
        $this->members = $session->getParty()->getMembers();
        unset($this->members[array_search($session, $this->members)]);

        foreach($this->members as $member) {
            $this->addButton($member->getUsername());
        }
    }

    public function setCallback(?int $result): void {
        $session = $this->getSession();
        if($result === null or !$session->hasParty()) return;

        $player = $session->getPlayer();
        if($session->isPartyLeader()) {
            $player->sendForm(new PartyMemberForm($this->members[$result], $session));
        } else {
            $player->sendForm(new PartyMembersForm($session));
        }
    }
}