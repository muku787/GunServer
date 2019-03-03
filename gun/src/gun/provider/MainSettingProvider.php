<?php

namespace gun\provider;

use pocketmine\utils\Config;

use gun\game\games\TeamDeathMatch;

class MainSettingProvider extends Provider
{

    const PROVIDER_ID = "mainsetting";
    /*ファイル名(拡張子はなし)*/
    const FILE_NAME = "mainsetting";
    /*セーブデータのバージョン*/
    const VERSION = 1;
    /*デフォルトデータ*/
    const DATA_DEFAULT = [
    						"GameMode" => TeamDeathMatch::GAME_ID,
                            "LobbyWorld" => ""
    					];

    public function open()
    {
        parent::open();
        if($this->data["LobbyWorld"] === "") $this->data["LobbyWorld"] = $this->plugin->getServer()->getDefaultLevel()->getFolderName();
    }

    public function getGameMode()
    {
    	return $this->data["GameMode"];
    }

}
























