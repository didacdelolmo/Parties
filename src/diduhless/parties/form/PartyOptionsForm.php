<?php


namespace diduhless\parties\form;


use diduhless\parties\event\PartySetPrivateEvent;
use diduhless\parties\event\PartySetPublicEvent;
use diduhless\parties\event\PartyUpdateSlotsEvent;
use diduhless\parties\session\Session;
use diduhless\parties\utils\ConfigGetter;
use diduhless\parties\utils\StoresSession;
use EasyUI\element\Label;
use EasyUI\element\Slider;
use EasyUI\element\Toggle;
use EasyUI\utils\FormResponse;
use EasyUI\variant\CustomForm;
use pocketmine\Player;

class PartyOptionsForm extends CustomForm {
    use StoresSession;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Party Options");
    }

    protected function onCreation(): void {
        $this->addElement("label", new Label("Change the party options in this window."));
        $this->addElement("party_public", new Toggle("Do you want to set your party public?"));
        $this->addElement("party_slots", new Slider("Set your maximum party slots", 1, ConfigGetter::getMaximumSlots(), $this->session->getParty()->getSlots()));
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        $party = $this->session->getParty();
        $public_response = $response->getToggleSubmittedChoice("party_public");
        $slots = (int) $response->getSliderSubmittedStep("party_slots");

        if($party->isPublic() !== $public_response) {
            $event = $public_response ? new PartySetPublicEvent($party, $this->session) : new PartySetPrivateEvent($party, $this->session);
            $event->call();
            if(!$event->isCancelled()) {
                $party->setPublic($public_response);
            }
        }
        if($party->getSlots() !== $slots) {
            $event = new PartyUpdateSlotsEvent($party, $this->session, $slots);
            $event->call();
            if(!$event->isCancelled()) {
                $party->setSlots($slots);
            }
        }
    }

}