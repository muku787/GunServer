<?php

namespace gun\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerChangeSkinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\network\mcpe\protocol\LoginPacket;

use gun\provider\AccountProvider;

class PlayerManagerListener implements Listener
{
	/*Mainクラスのオブジェクト*/
	private $plugin;
	/*PlayerManagerのオブジェクト*/
	private $manager;

	public function __construct($plugin, $manager)
	{
		$this->plugin = $plugin;
		$this->manager = $manager;
	}

	public function onJoin(PlayerJoinEvent $event)
	{
		$player = $event->getPlayer();
		$skin = $this->manager->getProcessedSkin($player);
		$player->setSkin($skin);
		$player->sendSkin();
	}

	public function onChangeSkin(PlayerChangeSkinEvent $event)
	{
		$event->setNewSkin($this->manager->getProcessedSkin($event->getPlayer(), $event->getNewSkin()));
	}

	public function onQuit(PlayerQuitEvent $event)
	{
		$this->manager->unsetData($event->getPlayer());
	}

	public function onDataPacketReceive(DataPacketReceiveEvent $event)
	{
		$pk = $event->getPacket();
		if($pk instanceof LoginPacket)
		{
			$this->manager->setOS($pk->username, $pk->clientData["DeviceOS"]);
		}
	}
}

