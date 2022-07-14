<?php

declare(strict_types=1);

namespace J0k3rrWild\PlayerParticle;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TF;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\scheduler\Task;
use pocketmine\scheduler\TaskScheduler;
use J0k3rrWild\PlayerParticle\task\Schelud;
use J0k3rrWild\PlayerParticle\Commands;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

//Particles
use pocketmine\world\particle\EnchantParticle;
use pocketmine\world\particle\EnchantmentTableParticle;
use pocketmine\world\particle\PortalParticle;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\particle\ExplodeParticle;
use pocketmine\world\particle\EntityFlameParticle;
use pocketmine\world\particle\WaterParticle;
use pocketmine\world\particle\WaterDripParticle;
use pocketmine\world\particle\LavaParticle;
use pocketmine\world\particle\LavaDripParticle;
use pocketmine\world\particle\HeartParticle;
use pocketmine\world\particle\AngryVillagerParticle;
use pocketmine\world\particle\HappyVillagerParticle;
use pocketmine\world\particle\CriticalParticle;
use pocketmine\world\particle\InkParticle;
use pocketmine\world\particle\SporeParticle;
use pocketmine\world\particle\SmokeParticle;
use pocketmine\world\particle\SnowballPoofParticle;
use pocketmine\world\particle\RedstoneParticle;
use pocketmine\world\particle\FloatingTextParticle;

use J0k3rrWild\PlayerParticle\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener{

public $deco;
public $particle;
public $colors = array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");
public $types = array("0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18");
public $unregister = array("particle");


    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder()."players/");
       
        $server = $this->getServer();
         //Unregister
         foreach($this->unregister as $disable){
          $commandMap = $this->getServer()->getCommandMap();
          $command = $commandMap->getCommand($disable);
          $command->setLabel($disable."_disabled");
          $command->unregister($commandMap);
          }
		
		$this->saveResource("config.yml");
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array());
		
        $server->getCommandMap()->register("particle", new Commands($this));
      }
	 
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
		
		switch($cmd->getName()){
			case "pui":
				if(!$sender instanceof Player){
					$sender->sendMessage("please use the command in game!");
					return true;
				}
				if($sender instanceof Player){
					$this->PUI($sender);
					return true;
				}
			break;
		}
	}
    
	public function PUI($player){
		$form = new SimpleForm(function($player, $data = null){
			if($data === null){
				return true;
			}
			if($data === 0){
				$this->getServer()->dispatchCommand($player, "pa disable");
				$player->sendMessage("§cParticle Disable.");
				return true;
			}
			
			$level = $player->getWorld();
			$getx = round($player->getPosition()->getX());
			$gety = round($player->getPosition()->getY());
			$getz = round($player->getPosition()->getZ());
			$vect = new Vector3($getx, $gety, $getz, $level);
			
			if($player->hasPermission($this->config->get($data)["Particle"]["Permission"])){
				$this->getServer()->dispatchCommand($player, "pa set ".$this->config->get($data)["Particle"]["Name"]);
				$this->getServer()->dispatchCommand($player, "pa enable");
				$player->sendMessage("§aParticle Enable.");
			} else {
				$player->sendMessage("§cYou do not have permission.");
			}
		});
		$content = str_replace (["{player}"], [$player->getName()], $this->config->get("Content"));
		$form->setTitle($this->config->get("Title"));
		$form->setContent($content);
		$form->addButton("§l§cDisable Particle\n§r§8Tap To Enter", 0, "textures/blocks/barrier");
		for($i = 1;$i <= 100;$i++){
			if($this->config->exists($i)){
				if($player->hasPermission($this->config->get($i)["Particle"]["Permission"])){
					$form->addButton($this->config->get($i)["Button"]["Name"], 0, "textures/ui/check");
				} else {
					$form->addButton($this->config->get($i)["Button"]["Name"], 0, "textures/ui/icon_lock");
				}
			}
		}
		$form->sendToPlayer($player);
		return true;
	}
	
	public function isOP($player, $type = NULL){
      if($type === NULL){
        return true;
      }else{
      
      $level = $player->getWorld();
      $getx = round($player->getPosition()->getX());
      $gety = round($player->getPosition()->getY());
      $getz = round($player->getPosition()->getZ());
      $vect = new Vector3($getx, $gety, $getz, $level);
       
      switch($type){
      case "0":
        $player->getWorld()->addParticle($vect, new PortalParticle()); 
        return true;
      case "1":
        $player->getWorld()->addParticle($vect, new FlameParticle()); 
        return true;
      case "2":
        $player->getWorld()->addParticle($vect, new EntityFlameParticle()); 
        return true;
      case "3":
        $player->getWorld()->addParticle($vect, new ExplodeParticle()); 
        return true;
      case "4":
        $player->getWorld()->addParticle($vect, new WaterParticle()); 
        return true;
      case "5":
        $player->getWorld()->addParticle($vect, new WaterDripParticle()); 
        return true;
      case "6":
        $player->getWorld()->addParticle($vect, new LavaParticle()); 
        return true;
      case "7":
        $player->getWorld()->addParticle($vect, new LavaDripParticle());
        return true;
      case "8":
        $player->getWorld()->addParticle($vect, new HeartParticle()); 
        return true;
      case "9":
        $player->getWorld()->addParticle($vect, new AngryVillagerParticle()); 
        return true;
      case "10":
        $player->getWorld()->addParticle($vect, new HappyVillagerParticle()); 
        return true;
      case "11":
        $player->getWorld()->addParticle($vect, new CriticalParticle());
        return true;
      case "12":
        $player->getWorld()->addParticle($vect, new EnchantmentTableParticle()); 
        return true;
      case "13":
        $player->getWorld()->addParticle($vect, new InkParticle()); 
        return true;
      case "14":
        $player->getWorld()->addParticle($vect, new SporeParticle()); 
        return true;
      case "15":
        $player->getWorld()->addParticle($vect, new SmokeParticle()); 
        return true;
      case "16":
        $player->getWorld()->addParticle($vect, new SnowballPoofParticle()); 
        return true;
      case "17":
        $player->getWorld()->addParticle($vect, new RedstoneParticle()); 
        return true;
      case "18":
        $task = new Schelud($this, $player); 
        $this->getScheduler()->scheduleDelayedTask($task,1*5); // Counted in ticks (1 second = 20 ticks)
        return true;
      }

      }
	}

    public function onJoinNew(PlayerJoinEvent $p){
      // var_dump($this->getDataFolder()."players/".$p->getPlayer()->getName());
      if(!is_dir($this->getDataFolder()."players/".strtolower($p->getPlayer()->getName()))){

          @mkdir($this->getDataFolder()."players/".strtolower($p->getPlayer()->getName()));
          $playerData = fopen($this->getDataFolder()."players/".strtolower($p->getPlayer()->getName())."/player.yaml", "w");
          $data = "Particle: true\nType: NULL";
          fwrite($playerData, $data);
          fclose($playerData);
          $this->deco = new Config($this->getDataFolder()."players/". strtolower($p->getPlayer()->getName()) . "/player.yaml", Config::YAML);
          
      }
      
  }


    public function onMove(PlayerMoveEvent $e){
      $player = $e->getPlayer();
      
    
      
      if($this->getServer()->isOp($player->getName()) === true){
        $this->deco = new Config($this->getDataFolder()."players/". strtolower($player->getName()) . "/player.yaml", Config::YAML);
        $status = $this->deco->get("Particle");
        $type = $this->deco->get("Type");
        if($status === false){
          return true;
        }else{
        $this->isOP($player, $type);
          return true;
        }
      }
      
      $level = $player->getWorld();
      $getx = round($player->getPosition()->getX());
      $gety = round($player->getPosition()->getY());
      $getz = round($player->getPosition()->getZ());
      $vect = new Vector3($getx, $gety, $getz, $level);

      $this->deco = new Config($this->getDataFolder()."players/". strtolower($player->getName()) . "/player.yaml", Config::YAML);
      $status = $this->deco->get("Particle");
      if($status === false){
        return true;
      }
      

      if($player->hasPermission("particle.portal")){
        $player->getWorld()->addParticle($vect, new PortalParticle($player)); 
      
    }
      if($player->hasPermission("particle.flame")){
        $player->getWorld()->addParticle($vect, new FlameParticle($player)); 
      
    }
      if($player->hasPermission("particle.entityflame")){
        $player->getWorld()->addParticle($vect, new EntityFlameParticle($player)); 
      
    } 
      if($player->hasPermission("particle.explode")){
        $player->getWorld()->addParticle($vect, new ExplodeParticle($player)); 
      
    }
      if($player->hasPermission("particle.water")){
        $player->getWorld()->addParticle($vect, new WaterParticle($player)); 
      
    }
      if($player->hasPermission("particle.waterdrip")){
        $player->getWorld()->addParticle($vect, new WaterDripParticle($player)); 
      
    }
      if($player->hasPermission("particle.lava")){
        $player->getWorld()->addParticle($vect, new LavaParticle($player)); 
      
    }
      if($player->hasPermission("particle.lavadrip")){
        $player->getWorld()->addParticle($vect, new LavaDripParticle($player)); 
      
    }
      if($player->hasPermission("particle.heart")){
        $player->getWorld()->addParticle($vect, new HeartParticle(10, $player)); 
      
    }
      if($player->hasPermission("particle.angryvillager")){
        $player->getWorld()->addParticle($vect, new AngryVillagerParticle($player)); 
      
    }
      if($player->hasPermission("particle.happyvillager")){
        $player->getWorld()->addParticle($vect, new HappyVillagerParticle($player)); 
      
    }
      if($player->hasPermission("particle.critical")){
        $player->getWorld()->addParticle($vect, new CriticalParticle($player)); 
      
    }
      if($player->hasPermission("particle.enchanttable")){
        $player->getWorld()->addParticle($vect, new EnchantmentTableParticle($player)); 
      
    }
      if($player->hasPermission("particle.ink")){
        $player->getWorld()->addParticle($vect, new InkParticle($player)); 
      
    }
      if($player->hasPermission("particle.spore")){
        $player->getWorld()->addParticle($vect, new SporeParticle($player)); 
      
    }
      if($player->hasPermission("particle.smoke")){
        $player->getWorld()->addParticle($vect, new SmokeParticle($player)); 
      
    } 
      if($player->hasPermission("particle.snowball")){
        $player->getWorld()->addParticle($vect, new SnowballPoofParticle($player)); 
      
    } 
      if($player->hasPermission("particle.redstone")){
        $player->getWorld()->addParticle($vect, new RedstoneParticle($player)); 
      
    } 
      
      if($player->hasPermission("particle.floatingtxt")){
        $task = new Schelud($this, $player); 
        $this->getScheduler()->scheduleDelayedTask($task,1*5); // Counted in ticks (1 second = 20 ticks)
  } 

    }



}
