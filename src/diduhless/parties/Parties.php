<?php

declare(strict_types=1);


namespace diduhless\parties;


use diduhless\parties\command\PartyChatCommand;
use diduhless\parties\command\PartyCommand;
use diduhless\parties\listener\ConfigurationListener;
use diduhless\parties\listener\PartyChatListener;
use diduhless\parties\listener\PartyEventListener;
use diduhless\parties\listener\SessionListener;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Parties extends PluginBase {

    static private self $instance;

    protected function onLoad(): void {
        self::$instance = $this;
        $this->saveDefaultConfig();
    }

    protected function onEnable(): void {
        $this->registerEvents(new SessionListener());
        $this->registerEvents(new PartyChatListener());
        $this->registerEvents(new PartyEventListener());
        $this->registerEvents(new ConfigurationListener());

        $this->registerCommand(new PartyCommand());
        $this->registerCommand(new PartyChatCommand());
    }

    private function registerEvents(Listener $listener): void {
       $this->getServer()->getPluginManager()->registerEvents($listener, $this);
   }

   public function registerCommand(Command $command): void {
        $this->getServer()->getCommandMap()->register("parties", $command);
   }

    public static function getInstance(): Parties {
        return self::$instance;
    }

}