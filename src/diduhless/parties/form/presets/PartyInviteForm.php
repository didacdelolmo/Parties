<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\event\PartyInviteEvent;
use diduhless\parties\form\PartyCustomForm;
use diduhless\parties\party\Invitation;
use diduhless\parties\session\Session;
use diduhless\parties\session\SessionFactory;

class PartyInviteForm extends PartyCustomForm {

    /** @var Session[] */
    private $sessions = [];

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
        if($options === null) {
            return;
        } elseif(isset($options[0]) and !empty($options[0])) {
            $this->attemptToInvite($options[0]);
        } else {
            $this->attemptToInvite($this->sessions[$options[1]]->getUsername());
        }
    }

    private function attemptToInvite(string $username): void {
        $session = $this->getSession();
        $target = SessionFactory::getSessionByName($username);

        if($target === null) {
            $session->message("{RED}The player {WHITE}$username {RED}is not online!");
        } elseif($target->hasParty()) {
            $session->message($target->getUsername() . " {RED}is already on a party!");
        } elseif($target->hasSessionInvitation($session)) {
            $session->message("{RED}You have already invited {WHITE}" . $target->getUsername() . " {RED}to your party!");
        } elseif(!$this->isCancelled($target)) {
            $this->sendInvitation($target);
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
        $target->addInvitation(new Invitation($session, $target, $session->getParty()->getId()));
    }

}