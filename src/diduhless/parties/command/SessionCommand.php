<?php


namespace diduhless\parties\command;


use diduhless\parties\Parties;
use diduhless\parties\session\Session;
use diduhless\parties\session\SessionFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

abstract class SessionCommand extends Command implements PluginIdentifiableCommand {

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if($this->testPermission($sender) and $sender instanceof Player and SessionFactory::hasSession($sender)) {
            $this->onCommand(SessionFactory::getSession($sender), $args);
        }
    }

    abstract public function onCommand(Session $session, array $args);

    public function getPlugin(): Plugin {
        return Parties::getInstance();
    }

}