<?php

namespace idk;

use mysqli;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class DatabaseManager {
    private mysqli $database;

    public function __construct(private PluginBase $plugin) {
        $config = new Config(Loader::getInstance()->getDataFolder() . "config.json", Config::JSON);
        $mysql = $config->get("database");

        $this->database = new mysqli(
            $mysql["host"], 
            $mysql["user"], 
            $mysql["password"], 
            $mysql["database"], 
            $mysql["port"]
        );
        
        if ($this->database->connect_error) {
            Loader::getInstance()->getLogger()->error("❌ Error en la conexión MySQL: " . $this->database->connect_error);
        } else {
            Loader::getInstance()->getLogger()->info("✅ Conexión a MySQL exitosa.");
        }
    }

    public function getDatabase(): mysqli {
        return $this->database;
    }
}