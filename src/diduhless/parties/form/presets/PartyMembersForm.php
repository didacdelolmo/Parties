<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\form\PartySimpleForm;
use diduhless\parties\session\Session;

class PartyMembersForm extends PartySimpleForm {

    /** @var Session[] */
    private $members;

    public function onCreation(): void {
        $this->setTitle("Party Members");
        $this->setContent("Current members in your party:");

        $session = $this->getSession();
        $members = $session->getParty()->getMembers();

        unset($members[array_search($session, $members, true)]);
        array_unshift($members, $session);

        $this->members = $members;
        foreach($this->members as $member) {
            $this->addButton($member->getUsername());
        }
    }

    public function setCallback(?int $result): void {
        $session = $this->getSession();
        if($result === null or !$session->hasParty()) return;

        $player = $session->getPlayer();
        $member = $this->members[$result];

        if($session->isPartyLeader() and !$member->isPartyLeader()) {
            $player->sendForm(new PartyMemberForm($member, $session));
        } else {
            $player->sendForm(new PartyMembersForm($session));
        }
    }
}