<?php

namespace gun\events;

use pocketmine\item\Item;

use gun\gameManager;
use gun\npcManager;
use gun\data\gunData;
use gun\data\playerData;

class PlayerJoinEvent extends Events {

	public function __construct($api){
		$this->api = $api;
		$this->playerData = playerData::getPlayerData();
	}

	public function call($ev){
		$player = $ev->getPlayer();
		$player->sendMessage('リロードはスニークして地面タッチです');
		$this->setWeapons($player);
		$name = $player->getName();
		/*if(gameManager::getTeam($player->getName())){
			gameManager::toSpawn($player);
			gameManager::setName($player);
		}else{
			gameManager::addMember($player);
		}*/
		$this->playerData->getAccount($name) ?? $this->playerData->createAccount($name);
		npcManager::addNPC($player);
	}
	
	 public function setWeapons($p){
    		if(($inv = $p->getInventory()) !== null){
	    		$weapons = $p->userdata['weapons'];
	    		$m = $weapons['main'];
	    		$s = $weapons['sub'];
	    		$g = $weapons['granade'];
	    		$k = $weapons['knife'];
    		}
    		$item = Item::get(280,0,1)->setCustomName('UziWaterPistol');
    		$gun = gunData::get('UziWaterPistol');
    		$lore = array("§a発射レート:".$gun['speed'], "§b火力:".$gun['damage'], "§cリロード:".$gun['reload'], "§d弾数:".$gun['max_ammo']);
    		$item->setLore($lore);
    		$p->getInventory()->addItem($item);
    	}
}
