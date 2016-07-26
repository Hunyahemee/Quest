<?php

namespace Quest;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\PlayerInventory;
use pocketmine\utils\Config;
use pocketmine\Player;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener{

   private $quest;
   
   private $player;
   
   private $eapi;

    public function onLoad(){
                $this->getLogger()->info("[Quest]Loading...");
                }
     public function onEnable(){
     // make files if it doesn't exist and read it if it exist
     		@mkdir($this->getDataFolder());
		if(!is_file($this->getDataFolder()."quests.yml")){
			$this->quests = new Config($this->getDataFolder()."quests.yml", Config::YAML, yaml_parse($this->readResource("quests.yml")));
		}else{
			$this->quests = new Config($this->getDataFolder()."quests.yml", Config::YAML);
		}
		$this->player = new Config($this->getDataFolder()."players.yml", Config::YAML);
     
              $this->getServer->getPluginManager()->registerEvents($this,$this);
              $this->getLogger()->info("[Quest]Plugin Enabled!");
              
              // get EconomyAPI
              $this->api = EconomyAPI::getInstance();
              }
              
              // read resources
              private function readResource($re){
		$path = $this->getFile()."resources/".$re;
		$resource = $this->getResource($re);
		if(!is_resource($resource)){
			$this->getLogger()->debug("Tried to load unknown resource ".TextFormat::AQUA.$res.TextFormat::RESET);
			return false;
		}
		$content = stream_get_contents($resource);
		@fclose($content);
		return $content;
	}
	
	public function onDisable(){
	      $this->player->save();
	      $this->quests->save();
	      }
	      
	      public function onCommand(CommandSender $sender, Command $command, $label, array $args, $event, $questname){
	      
	      $qu = $this->quests->get($questnames);
	      
		switch(array_shift($args)){
			case "join":
			if($sender->hasPermission("quest.command.join.use".$questname){
				if(!$sender instanceof Player){
					$sender->sendMessage("Please run this command in-game.");
				}
				if($this->player->exists($sender->getName())){
					$sender->sendMessage("You already have joined this quest.");
				}else{
					$quest = array_shift($args);
					if(trim($quest) === ""){
						$sender->sendMessage("Usage: /quest join <name>");
						break;
					}
					if($this->quests->exists($quest)){
						$this->player->set($sender->getName(), $quest);
						$sender->sendMessage("Quest \"$quest\" has successful added to your Quests list");
					}else{
						$sender->sendMessage("Quest \"$quest\" isn't avalible");
					}
				}
			}
				break;
			case "leave":
				if(!$sender instanceof Player){
					$sender->sendMessage("Please run this command in-game.");
				}
				if($this->player->exists($sender->getName())){
					$quest = $this->player->get($sender->getName());
					$this->player->remove($sender->getName());
					$sender->sendMessage("Quest \"$quest\" has been removed from your Quest list");
					$sender->sendMessage("Try hard next time!");
				}else{
					$sender->sendMessage("Quest isn't avaliable in your Quest list");
				}
				break;
				
				
				case "list":
				if(!$sender instanceof Player){
					$sender->sendMessage("Please run this command in-game.");
				}
				if($this->player->exists($sender->getName())){
					$sender->sendMessage("Your Currect Quest : ".$this->player->get($sender->getName()));
				}else{
					$sender->sendMessage("You haven't Join Any Quest");
				}
				break;
				
				
				case "finish":
				if(!$sender instanceof Player){
				              $sender->sendMessage("Please run this command in-game.");
				              }
				              if($this->player->exists($sender->getName())){
				              
				     $player = $event->getPlayer();
	       
	       $block = $event->getBlock();
	       
	       $inventory = $player->getInventory();
	       
	       $inv = $inventory->getContents();
	    
		$quest = $this->quests->get($this->player->get($player->getName()));
		
		$blockID = $quest[$block->getID()];
		
		$blockAmount = $quest[$block->getCount()];
		
		if($quest !== false){
			if(isset($quest[$block->getID().":".$block->getCount()."])){
				
				if($inv->getId() == $blockID && $inv->getCount() >= $blockAmount) {
				
			$sender->sendMessage(TextFormat::GOLD."Voltage".TextFormat::GRAY."Prison>>" TextFormat::BLUE."Price Received");
				
				     $money = $quest[$block->getID().":".$block->getCount()."];
				     if($money > 0){
				     $this->eapi->addMoney($player, $money);
				     $this->player->remove($sender->getName());
		}
		         else {
		         $this>eapi->reduceMoney($player, $money);
		         }
		  }
		}
    }
             else {
             $sender->sendMessage("You Didn't Joined a Quest yet.");
             }
             
             break;
             }
         }
				
	
	public function questIsComplete($item, $inventory, $player, $event) {
	
	   $player = $event->getPlayer();
	       
	       $block = $event->getBlock();
	       
	       $inventory = $player->getInventory();
	       
	       $inv = $inventory->getContents();
	    
		$quest = $this->quests->get($this->player->get($player->getName()));
		
		$blockID = $quest[$block->getID()];
		
		$blockAmount = $quest[$block->getCount()];
		
		if($quest !== false){
			if(isset($quest[$block->getID().":".$block->getCount()."])){
				$money = $quest[$block->getID().":".$block->getCount()."];
				
				if($inv->getId() == $blockID && $inv->getCount() >= 64) {
				$sender->sendMessage(TextFormat::GOLD."Voltage".TextFormat::GRAY."Prison>>" TextFormat::GREEN."Quest completed, use /quest finish to finish the quest and receive price");
				
					
				}
			}
		}
	}
}				
				
