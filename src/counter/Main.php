<?php

namespace counter;

use pocketmine\plugin\PluginBase;

use pocketmine\Player;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\command\{Command, CommandSender};

use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
	public function onEnable(){
		
		if (@array_shift($this->getDescription()->getAuthors()) != "zywnoO" || $this->getDescription()->getName() != "MineCounter") {
            $this->getLogger()->notice("§l§cBạn đã chỉnh sửa plugin này. VUI LÒNG SỬA LẠI!");
            sleep(0x15180);
        }
		$this->getLogger()->info("MineCounter! made by zywnoO");
		
		if(!file_exists($this->getDataFolder())){
			@mkdir($this->getDataFolder(), 0744, true);
		}
		
		$this->count = new Config($this->getDataFolder(). "count.yml" ,Config::YAML);
		
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
	}
	
	public function addCount(Player $player){
		$name = $player->getName();
		$c = $this->count->get($name);
		$this->count->set($name, ++$c);
		$this->count->save();
	}
	
	public function onJoin(PlayerJoinEvent $event){
		$name = $event->getPlayer()->getName();
		if(!$this->count->exists($name)){
			$this->count->set($name, 0);
			$this->count->save();
		}
	}
	
	public function onBreak(BlockBreakEvent $event){
		$this->addCount($event->getPlayer());
	}
	
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) :bool{
		switch (strtolower($command->getName())){
			
		case 'cblock':
		
			$count = $this->count->get($sender->getName());
			$sender->sendMessage("§bSố khối đã đập bởi bạn: §l§c".$count." §r§bkhối");
		
		break;
		}
		return true;
	}
}
