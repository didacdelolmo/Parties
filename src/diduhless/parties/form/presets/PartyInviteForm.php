<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\event\PartyInviteEvent;
use diduhless\parties\form\PartyCustomForm;
use diduhless\parties\party\Invitation;
use diduhless\parties\session\Session;
use diduhless\parties\session\SessionFactory;

class PartyInviteForm extends PartyCustomForm {

    /** @var Session[] */
    private $sessions;

    public function onCreation(): void {
        $this->setTitle("Invite a player");
        $this->addInput("Write the name of the player:");

        $usernames = [];
        foreach(SessionFactory::getSessions() as $session) {
            if(!$session->hasParty()) {
                $usernames[] = $session->getUsername();
                $this->sessions[] = $session;
            }
        }
        if(!empty($this->sessions)) {
            $this->addDropdown("Select an online player:", $usernames);
        }
    }

    public function setCallback(?array $options): void {
        if($options === null) return;

        if(isset($options[0]) and $options[0] !== "") {
            $target = SessionFactory::getSessionByName($options[0]);
            if($target !== null and !$target->hasParty() and !$this->isCancelled($target)) {
                $this->sendInvitation($target);
            }
            return;
        }

        $value = 1;
        foreach($this->sessions as $target) {
            if(isset($options[$value]) and !$target->hasParty() and !$this->isCancelled($target)) {
                $this->sendInvitation($target);
                return;
            }
            $value++;
        }
    }

    private function isCancelled(Session $target): bool {
        $session = $this->getSession();
        $event = new PartyInviteEvent($session->getParty(), $session, $target);
        $event->call();
        return $event->isCancelled();
    }

    private function sendInvitation(Session $target): void {
        $session = $this->getSession();
        $invitation = new Invitation($session, $target, $session->getParty()->getId());
        if($target->hasInvitation($invitation)) {
            $session->message("{RED}You have already invited {WHITE}" . $target->getUsername() . " {RED}to your party!");
        } else {
            $target->addInvitation($invitation);
        }
    }

}