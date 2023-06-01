<?php

namespace J0k3rrWild\PlayerParticle\task; 
use pocketmine\scheduler\Task; 
use J0k3rrWild\PlayerParticle\task\Schelud;
use J0k3rrWild\PlayerParticle\Main;
use pocketmine\level\particle\FloatingTextParticle; 


class ScheludRemove extends Task{

    public $vect;
    public $part;
    public $level;
    public function __construct(Schelud $plugin, $part, $vect, $level){ 
       $this->vect = $vect;
       $this->part = $part;
       $this->level = $level;
    } 


    public function onRun(): void{ 
        
        $this->part->setInvisible(true);
        $this->level->addParticle($this->vect, $this->part);
        
    }

}