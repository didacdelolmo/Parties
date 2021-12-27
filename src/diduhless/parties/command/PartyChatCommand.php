<?php


namespace diduhless\parties\command;


use diduhless\parties\session\Session;

class PartyChatCommand extends SessionCommand {

    public function __construct() {
        parent::__construct("pchat", "Toggles the party chat");
    }

    public function onCommand(Session $session, array $args) {
        $party_chat = $session->hasPartyChat();
        if(!$session->hasParty()) {
            $session->message("{RED}You must be in a party to do this!");
            return;
        }

        $session->setPartyChat(!$party_chat);
        if($party_chat) {
            $session->message("{GREEN}You have disabled the party chat.");
        } else {
            $session->message("{GREEN}Your messages will now be sent to the party chat!");
        }
    }

}