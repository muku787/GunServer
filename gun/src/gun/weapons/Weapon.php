<?php

namespace gun\weapons;

use pocketmine\utils\UUID;

use pocketmine\item\Item;

use pocketmine\entity\Attribute;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ByteTag;

abstract class Weapon
{
	/*その武器種のカテゴリー*/
	const CATEGORY = null;
	/*カテゴリータイプの値*/
	const CATEGORY_MAIN = 0;
	const CATEGORY_SUB = 1;
	/*その武器種のID*/
	const WEAPON_ID = "";
	/*武器種の名称*/
	const WEAPON_NAME = "";
	/*Loreに書く数値*/
	const ITEM_LORE = [];
	/*デフォルト武器のデータ*/
	const DEFAULT_DATA = [];

	const TAG_WEAPON = "weapon";
	const TAG_WEAPON_ID = "weapon_id";
	const TAG_TYPE = "type";
	const TAG_BULLET = "bullet";
	const TAG_UNIQUE_ID = "unique_id";

	const EVENT_PRE_INTERACT = "onPreInteract";
	const EVENT_INTERACT = "onInteract";
	const EVENT_SNEAK = "onSneak";
	const EVENT_WEAPON_ON = "onWeaponOn";
	const EVENT_WEAPON_OFF = "onWeaponOff";
	const EVENT_MOVE = "onMove";
	const EVENT_SHOOTBOW = "onShootBow";
	const EVENT_DROP_ITEM = "onDropItem";
	const EVENT_DEATH = "onDeath";
	const EVENT_KILL = "onKill";
	const EVENT_USE_FISHROD = "onUseFishRod";
	const EVENT_USE_ITEM_ON_ENTITY = "onUseItemOnEntity";

	/*Mainクラスのオブジェクト*/
	protected $plugin;
	/*武器の配列*/
	protected $weapons = [];

	public function __construct($plugin)
	{
		$this->plugin = $plugin;

		if(!file_exists($this->plugin->getDataFolder() . static::WEAPON_ID)){
			mkdir($this->plugin->getDataFolder() . static::WEAPON_ID);
		}

		$dir = $this->plugin->getDataFolder() . static::WEAPON_ID . "/";
		foreach(scandir($dir) as $file){
			if($file !== "." and $file !== ".."){
				$data = yaml_parse_file($dir . $file);
				$key = array_keys($data)[0];
				$this->weapons[$key] = $data[$key];
				$defaultData = current(static::DEFAULT_DATA);//以下更新処理(雑かも)
				foreach ($defaultData as $category => $categoryValue) {
					foreach ($categoryValue as $keyName => $value) {
						if(!isset($this->weapons[$key][$category][$keyName])) $this->weapons[$key][$category][$keyName] = $defaultData[$category][$keyName];
					}
				}
			}
		}

		if($this->weapons === []) $this->weapons = static::DEFAULT_DATA;
	}

	public function save()
	{
		foreach ($this->weapons as $key => $value) {
			$file = $this->plugin->getDataFolder() . static::WEAPON_ID . "/" . $key . ".yml";
			if(!file_exists($file)) touch($file);
			$data = [];
			$data[$key] = $value;
			yaml_emit_file($file, $data, YAML_UTF8_ENCODING);
		}
	}

	public function getCategory()
	{
		return static::CATEGORY;
	}

	public function getId()
	{
		return static::WEAPON_ID;
	}

	public function getName()
	{
		return static::WEAPON_NAME;
	}

	public function unset($id)
	{
		unset($this->weapons[$id]);
	}

	public function setData($id, $data)
	{
		$this->weapons[$id] = $data;
	}

	public function getData($id)
	{
		$data = null;
		if(isset($this->weapons[$id])) $data = $this->weapons[$id];
		return $data;
	}

	public function getDataAll()
	{
		return $this->weapons;
	}

	public function get($id)
	{
		if(!isset($this->weapons[$id])) return null;

		$item = Item::get($this->weapons[$id]["Item_Information"]["Item_Id"], $this->weapons[$id]["Item_Information"]["Item_Damage"], 1);

		$item->setCustomName($this->weapons[$id]["Item_Information"]["Item_Name"]);

		$lore = [];
		$lore[] = "§l§7§n" . static::WEAPON_NAME . "§r";
		foreach (static::ITEM_LORE as $datakey => $data) {
			foreach ($data as $key => $value) {
				$lore[] = "§3" . $value . ":§f" . $this->weapons[$id][$datakey][$key];
			}
		}
		$lore[] = "§f" . $this->weapons[$id]["Item_Information"]["Item_Lore"];
		$item->setLore($lore);

		$nbt = new CompoundTag(self::TAG_WEAPON);
		$nbt->setString(self::TAG_WEAPON_ID, static::WEAPON_ID);
		$nbt->setString(self::TAG_TYPE, $id);
		$nbt->setString(self::TAG_UNIQUE_ID, UUID::fromRandom()->toString());
		$item->setNamedTagEntry($nbt);
		$item->setNamedTagEntry(new ByteTag("Unbreakable", 1));

		return $item;
	}

	public function onInteract($player, $data)
	{

	}

	public function onPreInteract($player, $data)
	{

	}

	public function onWeaponOn($player, $data, $args)
	{
		$attribute = $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED);
		$attribute->setValue($player->isSprinting() ? ($attribute->getDefaultValue() * 1.3 * $data["Move"]["Move_Speed"]) : $attribute->getDefaultValue() * $data["Move"]["Move_Speed"], false, true);
	}

	public function onWeaponOff($player, $data, $args)
	{
		$attribute = $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED);
		$attribute->setValue($player->isSprinting() ? ($attribute->getDefaultValue() * 1.3) : $attribute->getDefaultValue(), false, true);
	}

	public function onSneak($player, $data)
	{

	}

	public function onMove($player, $data)
	{

	}

	public function onShootBow($player, $data, $args)
	{

	}

	public function onDropItem($player, $data, $args)
	{

	}

	public function onDeath($player, $data, $args)
	{
		
	}

	public function onKill($player, $data, $args)
	{
		
	}

	public function onUseFishRod($player, $data)
	{

	}

	public function onUseItemOnEntity($player, $data, $args)
	{
		$args[0]->setCancelled(true);
	}
}

