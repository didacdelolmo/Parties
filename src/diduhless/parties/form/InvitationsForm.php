<?php


namespace diduhless\parties\form;


use diduhless\parties\form\element\GoBackPartyButton;
use diduhless\parties\session\Session;
use diduhless\parties\utils\StoresSession;
use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;

class InvitationsForm extends SimpleForm {
    use StoresSession;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Party Invitations");
    }

    protected function onCreation(): void {
        $invitations = $this->session->getInvitations();

        if(!empty($invitations)) {
            $this->setHeaderText("These are your party invitations:");
            foreach($invitations as $invitation) {
                $this->addButton(new Button($invitation->getSender()->getUsername() . "'s Party", null, function(Player $player) use ($invitation) {
                    $player->sendForm(new ConfirmInvitationForm($this->session, $invitation));
                }));
            }
        } else {
            $this->setHeaderText("You do not have any invitations! :(");
        }
        $this->addButton(new GoBackPartyButton());
    }

}