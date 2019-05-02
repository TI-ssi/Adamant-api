<?php

namespace App\Library\Services;

use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class Battle
{

    protected $attackers;

    protected $defenders;

    protected $round;

    protected $log;
    
    public function __construct(){
                $this->round = 0 ;
    }

    public function initiate($atk, $def){
        $this->attackers = collect([
                                    ($atk->member1 != NULL?$atk->member1->toArray():['card_type'=>['hp'=> 0]]),
                                    ($atk->member2 != NULL?$atk->member2->toArray():['card_type'=>['hp'=> 0]]),
                                    ($atk->member3 != NULL?$atk->member3->toArray():['card_type'=>['hp'=> 0]])
                         ]);
        $this->defenders = collect([
            ($def->member1 != NULL?$def->member1->toArray():['card_type'=>['hp'=> 0]]),
            ($def->member2 != NULL?$def->member2->toArray():['card_type'=>['hp'=> 0]]),
            ($def->member3 != NULL?$def->member3->toArray():['card_type'=>['hp'=> 0]])
              ]);

        Log::debug('Received teams', [$atk, $def]);
        Log::debug('Received fighters', [$this->attackers->toArray(), $this->defenders->toArray()]);

        Log::info('Initiating fight of user # '.$atk->user_id.' against user # '.$def->user_id);
        $this->log[] = ('Initiating fight against user # '.$def->user_id);

    }
    
    //fight
    public function fight(){
        $fighting = true;
        while($fighting && !$this->deadTeam()) $fighting = $this->round();
        return $this->log;
    }

    protected function deadTeam(){
        $aliveAttackers = $aliveDefenders = 0;
        for($i = 0; $i < 3; $i++){
            if($this->isAlive($this->attackers[$i])) $aliveAttackers++;
            if($this->isAlive($this->defenders[$i])) $aliveDefenders++;
        }

        if($aliveAttackers == 0) {
            $this->log[] = ('Attacker died.');
            Log::info('Attacker died.');
            return 'attacker';
        } elseif($aliveDefenders == 0 ) {
            
            $this->log[] = ('Defender died.');
            Log::info('Defender died.');
            return 'defender';
        }

        return false;
    }

    protected function isAlive($unit){
        return $unit['card_type']['hp'] > 0;
    }

    protected function round(){


        Log::info('Round nb '.++$this->round);
        $this->log[] = ('Round nb '.$this->round);
        for($i = 0; $i < 3; $i++){
            if($this->isAlive($this->attackers[$i]) && !$this->deadTeam()) $this->attack($this->attackers[$i], $i);
            else $this->log[] = 'Attacker #'.($i+1).' is dead so unable to attack.';
            if($this->deadTeam()) return false;

            if($this->isAlive($this->defenders[$i]) && !$this->deadTeam()) $this->defend($this->defenders[$i], $i);
            else $this->log[] = 'Defender #'.($i+1).' is dead so unable to attack.';

            if($this->deadTeam()) return false;
        }
        return true;
    }
    
    protected function attack($unit, $pos){
        do{
        $rnd = rand(0,2);
        }while(!$this->isAlive($this->defenders[$rnd]));
        $dam = $this->damage($unit, $this->defenders[$rnd]);
        $unt = $this->defenders[$rnd];
        $unt['card_type']['hp'] -= $dam;

        $this->defenders[$rnd] =  $unt;

        
        Log::info("Attaker #$pos hit $dam dmg to defender #$rnd leaving ".$this->defenders[$rnd]['card_type']['hp']." hp");
        $this->log[] = ("Attaker #".($pos+1)." hit $dam dmg to defender #".($rnd+1)." leaving ".$this->defenders[$rnd]['card_type']['hp']." hp");
    }

    
    protected function defend($unit, $pos){
        do{
        $rnd = rand(0,2);
        }while(!$this->isAlive($this->attackers[$rnd]));
        $dam = $this->damage($unit, $this->attackers[$rnd]);
        $unt = $this->attackers[$rnd];
        $unt['card_type']['hp'] -= $dam;

        $this->attackers[$rnd] = $unt;

        Log::info("Defender #$pos hit $dam dmg to attacker #$rnd leaving ".$this->attackers[$rnd]['card_type']['hp']." hp");
        $this->log[] = ("Defender #".($pos+1)." hit $dam dmg to attacker #".($rnd+1)." leaving ".$this->attackers[$rnd]['card_type']['hp']." hp");
    }

    protected function damage($unit, $def){
        $damage = rand($unit['card_type']['min-dam'], $unit['card_type']['max-dam']);
        $damage -= $def['card_type']['def'];
        if($damage < 1) $damage = 1;
        return $damage;
    }
}
