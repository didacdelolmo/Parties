<?php


namespace diduhless\parties\form;


use diduhless\parties\party\Invitation;
use diduhless\parties\session\Session;
use diduhless\parties\utils\StoresSession;
use EasyUI\element\ModalOption;
use EasyUI\variant\ModalForm;
use pocketmine\player\Player;

class ConfirmInvitationForm extends ModalForm {
    use StoresSession;

    private Invitation $invitation;

    public function __construct(Session $session, Invitation $invitation) {
        $this->session = $session;
        $this->invitation = $invitation;
        parent::__construct("Join a party", "Do you want to join this party?", new ModalOption("Yes"), new ModalOption("No"));
    }

    protected function onAccept(Player $player): void {
        $this->invitation->attemptToAccept();
    }

    protected function onDeny(Player $player): void {
        $this->invitation->getTarget()->removeInvitation($this->invitation);
    }

}