<?php


namespace diduhless\parties\form;


use diduhless\parties\event\PartyLeaderPromoteEvent;
use diduhless\parties\event\PartyMemberKickEvent;
use diduhless\parties\form\element\GoBackButton;
use diduhless\parties\session\Session;
use diduhless\parties\utils\StoresSession;
use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;

class PartyMemberForm extends SimpleForm {
    use StoresSession;

    private Session $member;

    public function __construct(Session $session, Session $member) {
        $this->session = $session;
        $this->member = $member;
        parent::__construct("Party Member", "What do you want to do with this member?");
    }

    protected function onCreation(): void {
        $this->addKickButton();
        $this->addPromoteButton();
        $this->addButton(new GoBackButton(new PartyMembersForm($this->session)));
    }

    private function addKickButton(): void {
        $button = new Button("Kick him from the party");
        $button->setSubmitListener(function(Player $player) {
            if(!$this->member->isOnline()) return;
            $party = $this->session->getParty();

            $event = new PartyMemberKickEvent($party, $this->session, $this->member);
            $event->call();

            if(!$event->isCancelled()) {
                $party->remove($this->member);
            }
        });
        $this->addButton($button);
    }

    private function addPromoteButton(): void {
        $button = new Button("Promote to party leader");
        $button->setSubmitListener(function(Player $player) {
            if(!$this->member->isOnline()) return;
            $party = $this->session->getParty();

            $event = new PartyLeaderPromoteEvent($party, $this->session, $this->member);
            $event->call();

            if(!$event->isCancelled()) {
                $party->setLeader($this->member);
            }
        });
        $this->addButton($button);
    }

}