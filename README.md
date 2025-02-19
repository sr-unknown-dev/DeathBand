# DeathBand

**Versión**: 0.0.1

**Autor**: Sr.Idk(Alias "Ghost")

## Descripción

DeathBand es un plugin diseñado para servidores de Minecraft Pocket Edition (PMMP) que introduce un sistema de vidas y una mecánica de "Death Band". Este plugin permite a los administradores gestionar las vidas de los jugadores y teletransportarlos a un mundo específico cuando sus vidas se agotan. Además, los jugadores pueden ganar vidas adicionales al matar a otros jugadores.

## Características Principales

- **Sistema de Vidas**:
  - Cada jugador tiene un número de vidas almacenadas en un archivo YAML (`lives.yml`).
  - Los jugadores pueden ganar vidas adicionales al matar a otros jugadores.
  - Los administradores pueden agregar o remover vidas a los jugadores mediante comandos.

- **Death Band**:
  - Cuando un jugador se queda sin vidas, es teletransportado a un mundo específico configurado en el archivo de configuración (`config.yml`).
  - Los jugadores en el "Death Band" pueden ser teletransportados de vuelta al mundo principal si ganan vidas adicionales.

- **Comandos**:
  - `/live add <player> <amount>`: Agrega vidas a un jugador.
  - `/live remove <player> <amount>`: Remueve vidas a un jugador.
  - `/lives`: Muestra tu numero de vidas.
  - `/npcsdeathband <kit|modality>`: Spawnea NPCs específicos como Kit y Modality.

- **Eventos**:
  - **PlayerJoinEvent**: Verifica si el jugador ya está en el archivo de vidas y, si no está, lo agrega con 3 vidas.
  - **PlayerDeathEvent**: Maneja la lógica de reducción de vidas y teletransportación al "Death Band" cuando un jugador muere.

## Configuración

El plugin utiliza un archivo de configuración (`config.yml`) para definir el nombre del mundo de "Death Band" y otros ajustes relevantes.

## Requisitos

- PocketMine-MP (PMMP) 5.0.0 o superior.

## Instalación

1. Descarga el archivo `DeathBand.phar`.
2. Coloca el archivo `DeathBand.phar` en la carpeta `plugins` de tu servidor PocketMine-MP.
3. Reinicia el servidor para cargar el plugin.
4. Configura el archivo `config.yml` según tus necesidades.

## Licencia

Este plugin se distribuye bajo la GNU Lesser General Public License (LGPL). Puedes redistribuirlo y/o modificarlo bajo los términos de la LGPL, ya sea la versión 3 de la licencia o (a tu elección) cualquier versión posterior.

## Contacto

Para soporte y más información, puedes contactar al autor Ghost a través de [Discord](https://discord.gg/user/1061075896804593755)".
