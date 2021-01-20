<?php


namespace diduhless\parties\command;


use diduhless\parties\session\Session;

class PartyCommand extends SessionCommand {

    public function __construct() {
        parent::__construct("party", "Opens the party menu or sends a message to the party chat", null, ["p"]);
    }

    public function onCommand(Session $session, array $args) {
        if(isset($args[0])) {
            if($session->hasParty()) {
                $session->getParty()->sendColoredMessage($session, implode(" ", $args));
            } else {
                $session->message("{RED}You must be in a party to talk in the party chat!");
            }
        } else {
            $session->openPartyForm();
        }
    }

}