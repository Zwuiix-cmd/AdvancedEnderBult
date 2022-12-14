<?php

namespace Zwuiix\AdvancedEnderBult\items;

use pocketmine\entity\Location;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SetActorLinkPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityLink;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\player\Player;
use Zwuiix\AdvancedEnderBult\Main;

class EnderBult
{
    public function __construct() {}

    /**
     * @return string
     */
    public function getName(): string
    {
        return "§r§aEnderBult";
    }

    /**
     * @return string[]
     */
    public function getLore(): array
    {
        return ["§r§7All you have to do is right click", "§r§7To fly in the sky!"];
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function isEnderBult(Item $item): bool
    {
        if($item->getCustomName() === $this->getName() && $item->getLore() === $this->getLore()){
            return true;
        }
        return false;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return ItemFactory::getInstance()->get(ItemIds::ENDER_PEARL)->setCustomName($this->getName())->setLore($this->getLore());
    }

    /**
     * @param Player $player
     * @param int $count
     * @return void
     */
    public function give(Player $player, int $count = 1): void
    {
        $item=$this->getItem();
        $player->getInventory()->addItem($item->setCount($count));
    }

    public function onClickAir(Player $player): void
    {
        $enderPearl=new EnderPearl(Location::fromObject($player->getEyePos(), $player->getWorld(), $player->getLocation()->yaw, $player->getLocation()->pitch), $player);
        $enderPearl->setMotion($enderPearl->getDirectionVector()->multiply(2));
        $enderPearl->spawnToAll();

        $player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::RIDING, true);
        $player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::SITTING, true);
        $player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::WASD_CONTROLLED, true);
        $enderPearl->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::SADDLED, true);

        $player->getNetworkProperties()->setVector3(EntityMetadataProperties::RIDER_SEAT_POSITION, new Vector3(0, 1.5, 0), true);

        $data = new EntityLink($enderPearl->getId(), $player->getId(), EntityLink::TYPE_RIDER, true, true);
        $pk = SetActorLinkPacket::create($data);

        foreach ($enderPearl->getViewers() as $viewer){
            $viewer->getNetworkSession()->sendDataPacket($pk);
        }

        Main::$enderBult[$player->getName()]=$enderPearl;
    }
}