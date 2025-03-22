# DeathBand Plugin
## Overview
DeathBand is a PocketMine-MP plugin that adds a unique hardcore-style gameplay mechanic where players have limited lives and are teleported to different worlds based on their death status.
## Features
- Life system with MySQL database integration
- Automatic teleportation between worlds on death
- Custom world configuration
- Death tracking and management
- PocketMine-MP 5.0.0 or higher
- MySQL server for storing player data
- PHP with MySQLi extension enabled
## Installation
1. **Clone the Repository**
   ```bash
   git clone https://github.com/sr-unknown-dev/DeathBand.git
   ```
2. **Configure the Plugin**

- Edit the config.yml file located in the resources directory to set up your database connection and world names.
- Set Up MySQL Database:

- Ensure your MySQL server is running.
- Create a database and configure the connection details in config.yml .
- Start the Server:

- Place the plugin in the plugins directory of your PocketMine-MP server.
- Start the server to load the plugin.

## Configuration
Edit the config.yml file to customize the plugin settings:
name-world-modaly: world
teleport.name.deathband: deathband

database:
  host: "your_host"
  user: "your_user"
  password: "your_password"
  database: "your_database"
  port: 3306

## Usage
- Players' lives are managed through the plugin, and they can be teleported to specific worlds based on the configuration.
- Use the provided commands to interact with the plugin and manage player lives.
## Contributing
Contributions are welcome! Please fork the repository and submit a pull request for any improvements or bug fixes.

## License
This project is licensed under the GNU Lesser General Public License. See the LICENSE file for more details.

## Contact
For any questions or support, please contact [Discord](https://discord.com/users/1061075896804593755).

Make sure to replace placeholders like
`your_host`,
`your_user`,
`your_password`, 
`your_database`,
`your_port`,
and contact information with actual values relevant to your project.