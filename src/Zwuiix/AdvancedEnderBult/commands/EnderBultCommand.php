<?php

namespace Zwuiix\AdvancedEnderBult\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\GiveCommand;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use Zwuiix\AdvancedEnderBult\items\EnderBult;
use Zwuiix\AdvancedEnderBult\lib\CortexPE\Commando\args\IntegerArgument;
use Zwuiix\AdvancedEnderBult\lib\CortexPE\Commando\args\RawStringArgument;
use Zwuiix\AdvancedEnderBult\lib\CortexPE\Commando\BaseCommand;
use Zwuiix\AdvancedEnderBult\lib\CortexPE\Commando\BaseSubCommand;
use Zwuiix\AdvancedEnderBult\lib\CortexPE\Commando\exception\ArgumentOrderException;

class EnderBultCommand extends BaseCommand
{
    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {
        $this->registerSubCommand(new EnderBultAboutCommand("about"));
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->registerArgument(1, new IntegerArgument("count", true));
        $this->setPermission("advancedenderbult.enderbult");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $player=Server::getInstance()->getPlayerByPrefix($args["player"]);
        $count=1;
        if(isset($args["count"])) $count = $args["count"];
        if(!$player instanceof Player){
            $sender->sendMessage(KnownTranslationFactory::commands_generic_player_notFound()->prefix(TextFormat::RED));
            return;
        }

        $enderBult=new EnderBult();
        $enderBult->give($player, $count);
        Command::broadcastCommandMessage($sender, KnownTranslationFactory::commands_give_success(
            $enderBult->getName()."§r",
            (string) $count,
            $player->getName()
        ));
    }
}

class EnderBultAboutCommand extends BaseSubCommand
{
    protected function prepare(): void{}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $sender->sendMessage("§2=== §aEnderBult §2===");
        $sender->sendMessage("Author: §eZwuiix#0001");
        $sender->sendMessage("Github: §ehttps://github.com/Zwuiix-cmd");
        $sender->sendMessage("Version: §e1.0.0");
    }
}