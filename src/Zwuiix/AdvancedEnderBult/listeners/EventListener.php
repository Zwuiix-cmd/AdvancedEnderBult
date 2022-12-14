<?php

namespace Zwuiix\AdvancedEnderBult\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\player\Player;
use Zwuiix\AdvancedEnderBult\items\EnderBult;
use Zwuiix\AdvancedEnderBult\Main;

class EventListener implements Listener
{
    public function onUse(PlayerItemUseEvent $event)
    {
        $player=$event->getPlayer();
        $item=$event->getItem();

        $enderBult=new EnderBult();
        if(!$enderBult->isEnderBult($item))return;
        if(isset(Main::$enderBult[$player->getName()])){
            $enderPearl=Main::$enderBult[$player->getName()];
            if(!$enderPearl->isAlive() or !$enderPearl->isClosed()){
                $enderPearl->flagForDespawn();
            }
        }
        $event->cancel();
        $enderBult->onClickAir($player);
    }

    public function handlePacket(DataPacketReceiveEvent $event)
    {
        $packet = $event->getPacket();
        $player = $event->getOrigin()->getPlayer();

        if(!$player instanceof Player)return;
        if (!$packet instanceof InteractPacket)return;
        if($packet->action != InteractPacket::ACTION_LEAVE_VEHICLE)return;
        $entity = $player->getWorld()->getEntity($packet->targetActorRuntimeId);

        if(!isset(Main::$enderBult[$player->getName()]))return;
        $enderBult=Main::$enderBult[$player->getName()];

        if($entity!==$enderBult)return;
        $entity->flagForDespawn();
    }
}