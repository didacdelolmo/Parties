<?php

declare(strict_types=1);


namespace diduhless\parties;


use diduhless\parties\listener\ConfigurationListener;
use diduhless\parties\listener\PartyEventListener;
use diduhless\parties\listener\PlayerJoinListener;
use diduhless\parties\listener\SessionListener;
use diduhless\parties\party\PartyCommand;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Parties extends PluginBase {

    /** @var Parties */
    static private $instance;

    public function onLoad() {
        self::$instance = $this;

        $dataFolder = $this->getDataFolder();
        if(!is_dir($dataFolder)) {
            mkdir($dataFolder);
        }
        $this->saveDefaultConfig();
    }

    public function onEnable() {
       $this->registerEvents(new SessionListener());
       $this->registerEvents(new PlayerJoinListener());
       $this->registerEvents(new PartyEventListener());
       $this->registerEvents(new ConfigurationListener());
       $this->getServer()->getCommandMap()->register("parties", new PartyCommand());
   }

   private function registerEvents(Listener $listener): void {
       $this->getServer()->getPluginManager()->registerEvents($listener, $this);
   }

    public static function getInstance(): Parties {
        return self::$instance;
    }

}