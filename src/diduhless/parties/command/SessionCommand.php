<?php


namespace diduhless\parties\command;


use diduhless\parties\Parties;
use diduhless\parties\session\Session;
use diduhless\parties\session\SessionFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

abstract class SessionCommand extends Command implements PluginOwned {

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if($this->testPermission($sender) and $sender instanceof Player and SessionFactory::hasSession($sender)) {
            $this->onCommand(SessionFactory::getSession($sender), $args);
        }
    }

    abstract public function onCommand(Session $session, array $args);

    public function getOwningPlugin(): Plugin {
        return Parties::getInstance();
    }

}