<?php


namespace diduhless\parties\form;


use diduhless\parties\event\PartyCreateEvent;
use diduhless\parties\party\Party;
use diduhless\parties\party\PartyFactory;
use diduhless\parties\session\Session;
use diduhless\parties\utils\StoresSession;
use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;

class PartyMenuForm extends SimpleForm {
    use StoresSession;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Party Menu", "You do not have a party! Create a party or accept an invitation to join a party.");
    }

    protected function onCreation(): void {
        $this->addCreatePartyButton();
        $this->addPublicPartiesButton();
        $this->addInvitationsButton();
    }

    private function addCreatePartyButton(): void {
        $button = new Button("Create a party");
        $button->setSubmitListener(function(Player $player) {
            $party = new Party(uniqid(), $this->session);
            $event = new PartyCreateEvent($party, $this->session);

            $event->call();
            if(!$event->isCancelled()) {
                $party->add($this->session);
                PartyFactory::addParty($party);
                $this->session->openPartyForm();
            }
        });
        $this->addButton($button);
    }

    public function addPublicPartiesButton(): void {
        $this->addButton(new Button("Join a public party", null, function(Player $player) {
            $player->sendForm(new PublicPartiesForm($this->session));
        }));
    }

    public function addInvitationsButton(): void {
        $this->addButton(new Button("Invitations [" . count($this->session->getInvitations()) . "]", null, function(Player $player) {
            $player->sendForm(new InvitationsForm($this->session));
        }));
    }

}