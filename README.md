Aquí tienes un README.md bien estructurado para tu plugin con soporte MySQL en PocketMine 5:

# MySQL PocketMine Plugin

Este plugin conecta un servidor **PocketMine-MP 5** a una base de datos **MySQL**, permitiendo almacenar información de jugadores.

## 🚀 Características

- Conexión segura a MySQL.
- Creación automática de tablas.
- Inserción y consulta de datos de jugadores.
- Código optimizado y fácil de extender.

## 📌 Requisitos

- **PocketMine-MP 5** instalado.
- **MySQL Server** en tu VPS o localhost.
- **Extensión MySQLi habilitada en PHP**.

## 🔧 Instalación

1. Instala MySQL en tu VPS (si no lo tienes):
   ```sh
   sudo apt update
   sudo apt install mysql-server
   sudo mysql_secure_installation

2. Crea la base de datos:

mysql -u root -p
CREATE DATABASE pocketmine;


3. Agrega la configuración en plugin.yml y crea un archivo config.yml dentro del plugin con:

mysql:
  host: "127.0.0.1"
  user: "root"
  password: ""
  database: "pocketmine"
  port: 3306



📜 Código

1️⃣ Clase de conexión MySQL

<?php

declare(strict_types=1);

namespace GhostlyNetwork\database;

use mysqli;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class DatabaseManager {

    private mysqli $database;

    public function __construct(private PluginBase $plugin) {
        $config = new Config($this->plugin->getDataFolder() . "config.yml", Config::YAML);

        $mysql = $config->get("mysql");
        $this->database = new mysqli($mysql["host"], $mysql["user"], $mysql["password"], $mysql["database"], $mysql["port"]);

        if ($this->database->connect_error) {
            $this->plugin->getLogger()->error("Error en la conexión MySQL: " . $this->database->connect_error);
        } else {
            $this->plugin->getLogger()->info("✅ Conexión a MySQL exitosa.");
        }
    }

    public function getDatabase(): mysqli {
        return $this->database;
    }
}

2️⃣ Creación de tabla en onEnable()

use GhostlyNetwork\database\DatabaseManager;

class Main extends PluginBase {

    private DatabaseManager $db;

    public function onEnable(): void {
        $this->db = new DatabaseManager($this);
        $this->createTables();
    }

    private function createTables(): void {
        $sql = "CREATE TABLE IF NOT EXISTS players (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(16) NOT NULL UNIQUE,
            coins INT DEFAULT 0
        )";

        if ($this->db->getDatabase()->query($sql)) {
            $this->getLogger()->info("Tabla 'players' creada correctamente.");
        } else {
            $this->getLogger()->error("Error al crear la tabla: " . $this->db->getDatabase()->error);
        }
    }
}

3️⃣ Insertar y consultar datos

public function insertPlayer(string $name, int $coins = 0): void {
    $stmt = $this->db->getDatabase()->prepare("INSERT INTO players (name, coins) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $coins);
    $stmt->execute();
    $stmt->close();
}

public function getCoins(string $name): ?int {
    $stmt = $this->db->getDatabase()->prepare("SELECT coins FROM players WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $coins = $result->fetch_assoc()["coins"] ?? null;
    $stmt->close();
    return $coins;
}

📖 Recursos adicionales

Documentación oficial de MySQL

Curso PHP & MySQL en W3Schools


📩 Contacto

Si tienes dudas o sugerencias, contáctame en mi GitHub: Ghost-zzz


---

¡Listo! Este README es profesional y fácil de entender. ¿Quieres que agregue algo más?

