<?php


namespace diduhless\parties\party;


use diduhless\parties\session\SessionFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class PartyCommand extends Command {

    public function __construct() {
        parent::__construct("party", "Open the party menu");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if($sender instanceof Player and SessionFactory::hasSession($sender)) {
            SessionFactory::getSession($sender)->openPartyForm();
        }
    }

}