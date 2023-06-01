<?php 

namespace J0k3rrWild\PlayerParticle\task; 

use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use J0k3rrWild\PlayerParticle\Main;
use J0k3rrWild\PlayerParticle\task\ScheludRemove;
use pocketmine\world\particle\FloatingTextParticle; 


class Schelud extends Task{

    public $plugin;
    public $player;
    public function __construct(Main $plugin, $player){ 
       $this->plugin = $plugin; 
       $this->player = $player; 
       
    } 


    public function onRun(): void{ 
        
        $colors = array_rand($this->plugin->colors);
        $level = $this->player->getWorld();
        $x = round($this->player->getPosition()->getX());
        $y = round($this->player->getPosition()->getY());
        $z = round($this->player->getPosition()->getZ());
        $vect = new Vector3($x, $y, $z, $level);
        $this->plugin->particle = new FloatingTextParticle("§{$colors}{$this->player->getName()}");
        $level->addParticle($vect, $this->plugin->particle);
        
        
        $task = new ScheludRemove($this, $this->plugin->particle, $vect, $level); 
        $this->plugin->getScheduler()->scheduleDelayedTask($task, 1*20);
    }

}