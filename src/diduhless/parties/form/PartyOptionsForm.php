<?php


namespace diduhless\parties\form;


use diduhless\parties\event\PartyPvpDisableEvent;
use diduhless\parties\event\PartyPvpEnableEvent;
use diduhless\parties\event\PartySetPrivateEvent;
use diduhless\parties\event\PartySetPublicEvent;
use diduhless\parties\event\PartyUpdateSlotsEvent;
use diduhless\parties\event\PartyWorldTeleportDisableEvent;
use diduhless\parties\event\PartyWorldTeleportEnableEvent;
use diduhless\parties\session\Session;
use diduhless\parties\utils\ConfigGetter;
use diduhless\parties\utils\StoresSession;
use EasyUI\element\Label;
use EasyUI\element\Slider;
use EasyUI\element\Toggle;
use EasyUI\utils\FormResponse;
use EasyUI\variant\CustomForm;
use pocketmine\player\Player;

class PartyOptionsForm extends CustomForm {
    use StoresSession;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Party Options");
    }

    protected function onCreation(): void {
        $party = $this->session->getParty();

        $this->addElement("label", new Label("Change the party options in this window."));
        $this->addElement("public", new Toggle("Do you want to set your party public?", $party->isPublic()));
        if(ConfigGetter::isPvpDisabledOption()) {
            $this->addElement("disable_pvp", new Toggle("Do you want to disable the damage between the members of your party?", !$party->isPvp()));
        }
        if(ConfigGetter::isWorldTeleportOption()) {
            $this->addElement("leader_world_teleport", new Toggle("Do you want to teleport the members of your party when the leader moves to another world?", $party->isLeaderWorldTeleport()));
        }
        $this->addElement("slots", new Slider("Set your maximum party slots", 1, ConfigGetter::getMaximumSlots(), $party->getSlots(), 1));
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        $party = $this->session->getParty();
        $public = $response->getToggleSubmittedChoice("public");
        $slots = (int) $response->getSliderSubmittedStep("slots");

        if($party->isPublic() !== $public) {
            $event = $public ? new PartySetPublicEvent($party, $this->session) : new PartySetPrivateEvent($party, $this->session);
            $event->call();
            if(!$event->isCancelled()) {
                $party->setPublic($public);
            }
        }
        if(ConfigGetter::isPvpDisabledOption()) {
            $disable_pvp = $response->getToggleSubmittedChoice("disable_pvp");
            if($party->isPvp() === $disable_pvp) {
                $event = $disable_pvp ? new PartyPvpDisableEvent($party, $this->session) : new PartyPvpEnableEvent($party, $this->session);
                $event->call();
                if(!$event->isCancelled()) {
                    $party->setPvp(!$disable_pvp);
                }
            }
        }
        if(ConfigGetter::isWorldTeleportOption()) {
            $leader_world_teleport = $response->getToggleSubmittedChoice("leader_world_teleport");
            if($party->isLeaderWorldTeleport() !== $leader_world_teleport) {
                $event = $leader_world_teleport ? new PartyWorldTeleportEnableEvent($party, $this->session) : new PartyWorldTeleportDisableEvent($party, $this->session);
                $event->call();
                if(!$event->isCancelled()) {
                    $party->setLeaderWorldTeleport($leader_world_teleport);
                }
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