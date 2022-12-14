<?php

namespace Zwuiix\AdvancedEnderBult;

use JsonException;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use Zwuiix\AdvancedEnderBult\trait\LoaderTrait;

class Main extends PluginBase
{
    /**
     * @var EnderPearl[]
     */
    public static array $enderBult = array();

    use SingletonTrait, LoaderTrait;

    /**
     * @throws JsonException
     */
    protected function onEnable(): void
    {
        $this->init();
    }

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

}