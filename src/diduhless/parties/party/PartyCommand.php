<?php


namespace diduhless\parties\party;


use diduhless\parties\Parties;
use diduhless\parties\session\SessionFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class PartyCommand extends Command implements PluginIdentifiableCommand {

    public function __construct() {
        parent::__construct("party", "Opens the party menu or sends a message to the party chat", null, ["p"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender instanceof Player or !SessionFactory::hasSession($sender)) {
            return;
        }
        $session = SessionFactory::getSession($sender);

        if(isset($args[0])) {
            if($session->hasParty()) {
                $session->getParty()->message("{LIGHT_PURPLE}[Party] {GRAY}" . $sender->getName() . ": {WHITE}" . $message = implode(" ", $args));
            } else {
                $session->message("{RED}You must be in a party to talk in the party chat!");
            }
        } else {
            $session->openPartyForm();
        }
    }

    public function getPlugin(): Plugin {
        return Parties::getInstance();
    }

}