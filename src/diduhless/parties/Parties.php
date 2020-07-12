<?php

declare(strict_types=1);


namespace diduhless\parties;


use diduhless\parties\listener\SessionListener;
use diduhless\parties\party\PartyCommand;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Parties extends PluginBase {

    /** @var Parties */
    static private $instance;

    public function onLoad() {
        self::$instance = $this;
    }

    public function onEnable() {
       $this->registerEvents(new SessionListener());
       $this->getServer()->getCommandMap()->register("party", new PartyCommand());
   }

   private function registerEvents(Listener $listener): void {
       $this->getServer()->getPluginManager()->registerEvents($listener, $this);
   }

    public static function getInstance(): Parties {
        return self::$instance;
    }

}