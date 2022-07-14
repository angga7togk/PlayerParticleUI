<?php

declare(strict_types=1);

namespace J0k3rrWild\PlayerParticle;


use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use pocketmine\scheduler\Task;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\plugin\{PluginOwned, PluginOwnedTrait};
use pocketmine\command\utils\InvalidCommandSyntaxException;
use J0k3rrWild\PlayerParticle\Main;
use pocketmine\Server;


class Commands extends Command implements PluginOwned{
    use PluginOwnedTrait;
	
    public function __construct(Main $plugin){
		parent::__construct("particle", "Particle main command", "/pa [disable/enable]", ["pa"]);
		
        $this->plugin = $plugin;
	}

    public function execute(CommandSender $p, string $label, array $args) : bool{
        if(!isset($args[0])){ 
            throw new InvalidCommandSyntaxException;
            return false;
       }
       
        if(!($p instanceof Player)){
            $p->sendMessage("§c You can use this command only in-game!");
            return true;
        }
       
       
        switch(strtolower($args[0])){
          case "disable":
            $this->plugin->deco = new Config($this->plugin->getDataFolder()."players/". strtolower($p->getName()) . "/player.yaml", Config::YAML);
            $this->plugin->deco->get("Particle");
            $this->plugin->deco->set("Particle", false);
            $this->plugin->deco->save();

            break;
          case "enable":
            $this->plugin->deco = new Config($this->plugin->getDataFolder()."players/". strtolower($p->getName()) . "/player.yaml", Config::YAML);
            $this->plugin->deco->get("Particle");
            $this->plugin->deco->set("Particle", true);
            $this->plugin->deco->save();
            break;
		  case "set":
          case "list":
            if(($this->plugin->getServer()->isOp($p->getName()) === true)){
              if(!isset($args[1])){
                $p->sendMessage(TF::GREEN."|--------------------[PlayerParticle]--------------------|");
                $p->sendMessage(TF::GOLD."To set, use /pa set <number>");
                $p->sendMessage(TF::GREEN."0 - PortalParticle");
                $p->sendMessage(TF::GREEN."1 - FlameParticle");
                $p->sendMessage(TF::GREEN."2 - EntityFlameParticle");
                $p->sendMessage(TF::GREEN."3 - ExplodeParticle");
                $p->sendMessage(TF::GREEN."4 - WaterParticle");
                $p->sendMessage(TF::GREEN."5 - WaterDripParticle");
                $p->sendMessage(TF::GREEN."6 - LavaParticle");
                $p->sendMessage(TF::GREEN."7 - LavaDripParticle");
                $p->sendMessage(TF::GREEN."8 - HeartParticle");
                $p->sendMessage(TF::GREEN."9 - AngryVillagerParticle");
                $p->sendMessage(TF::GREEN."10 - HappyVillagerParticle");
                $p->sendMessage(TF::GREEN."11 - CriticalParticle");
                $p->sendMessage(TF::GREEN."12 - EnchantTableParticle");
                $p->sendMessage(TF::GREEN."13 - InkParticle");
                $p->sendMessage(TF::GREEN."14 - SporeParticle");
                $p->sendMessage(TF::GREEN."15 - SmokeParticle");
                $p->sendMessage(TF::GREEN."16 - SnowballParticle");
                $p->sendMessage(TF::GREEN."17 - RedstoneParticle");
                $p->sendMessage(TF::GREEN."18 - FloatingTextParticle [Nick rainbow!!]");
                break;
              }
              $this->plugin->deco = new Config($this->plugin->getDataFolder()."players/". strtolower($p->getName()) . "/player.yaml", Config::YAML);
              if(in_array($args[1], $this->plugin->types)){
                $this->plugin->deco->get("Type");
                $this->plugin->deco->set("Type", $args[1]);
                $this->plugin->deco->save();
              }else{
                $p->sendMessage("§c Particles not found, use");
              }
            }else{
              $p->sendMessage("§c You need OP permissions to use that!");
            }
        }
        return true;
      }
}