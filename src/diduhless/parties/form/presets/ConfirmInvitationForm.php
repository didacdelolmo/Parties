<?php


namespace diduhless\parties\form\presets;


use diduhless\parties\form\PartyModalForm;
use diduhless\parties\party\Invitation;
use diduhless\parties\session\Session;

class ConfirmInvitationForm extends PartyModalForm {

    /** @var Invitation */
    private $invitation;

    public function __construct(Invitation $invitation, Session $session) {
        $this->invitation = $invitation;
        parent::__construct($session);
    }

    public function onCreation(): void {
        $this->setTitle("Confirm the invitation");
        $this->setContent("Do you want to join this party?");
        $this->setButton1("Yes");
        $this->setButton2("No");
    }

    public function setCallback(?bool $result): void {
        if($result !== null and $result) {
            $this->invitation->attemptToAccept();
        }
    }
}