<?php


namespace diduhless\parties\form;


use diduhless\parties\form\element\GoBackPartyButton;
use diduhless\parties\party\Invitation;
use diduhless\parties\party\PartyFactory;
use diduhless\parties\session\Session;
use diduhless\parties\utils\StoresSession;
use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;

class PublicPartiesForm extends SimpleForm {
    use StoresSession;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Join a public party");
    }

    protected function onCreation(): void {
        foreach(PartyFactory::getParties() as $party) {
            if($party->isPublic() and !$party->isFull()) {
                $this->addButton(new Button($party->getLeaderName() . "'s Party", null, function(Player $player) use ($party) {
                    $player->sendForm(new ConfirmInvitationForm($this->session, new Invitation($party->getLeader(), $this->session, $party->getId())));
                }));
            }
        }
        if(!empty($this->getButtons())) {
            $this->setHeaderText("Press on the party you want to join!");
        } else {
            $this->setHeaderText("There are no public parties to join! :(");
        }
        $this->addButton(new GoBackPartyButton());
    }

}