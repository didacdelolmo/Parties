<?php


namespace diduhless\parties\form;


use diduhless\parties\form\element\GoBackPartyButton;
use diduhless\parties\session\Session;
use diduhless\parties\utils\StoresSession;
use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;

class PartyMembersForm extends SimpleForm {
    use StoresSession;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Party Members", "Current members in your party:");
    }

    protected function onCreation(): void {
        $members = $this->session->getParty()->getMembers();
        unset($members[array_search($this->session, $members, true)]);
        array_unshift($members, $this->session);

        foreach($members as $member) {
            $button = new Button($member->getUsername());
            $button->setSubmitListener(function(Player $player) use ($member) {
                if($this->session->isPartyLeader() and !$member->isPartyLeader()) {
                    $player->sendForm(new PartyMemberForm($this->session, $member));
                } else {
                    $player->sendForm(new PartyMembersForm($this->session));
                }
            });
            $this->addButton($button);
        }
        $this->addButton(new GoBackPartyButton());
    }

}