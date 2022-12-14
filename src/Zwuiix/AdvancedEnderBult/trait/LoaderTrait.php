<?php

namespace Zwuiix\AdvancedEnderBult\trait;

use JsonException;
use Zwuiix\AdvancedEnderBult\commands\EnderBultCommand;
use Zwuiix\AdvancedEnderBult\lib\CortexPE\Commando\exception\HookAlreadyRegistered;
use Zwuiix\AdvancedEnderBult\lib\CortexPE\Commando\PacketHooker;
use Zwuiix\AdvancedEnderBult\listeners\EventListener;

trait LoaderTrait
{
    /**
     * @return void
     * @throws JsonException
     * @throws HookAlreadyRegistered
     */
    public function init(): void
    {
        if(!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }
        $this->getServer()->getCommandMap()->register("enderbult", new EnderBultCommand($this, "enderbult", "Give an enderbult to a connected player!"));
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }
}
