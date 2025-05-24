A database-driven web music player built with PHP, MySQL, and Bootstrap.

Features
- User login and authentication
- Playlist creation, editing, and management
- Song search and simulated playback
- Follow artists and track top artists per country
- Dynamic queries and custom SQL interactions
- Bootstrap-powered responsive design

Structure
📁 php/
├── index.php
├── login.php
├── homepage.php
├── artistpage.php
├── currentmusic.php
├── playlistpage.php
└── install.php

📁 sql/
├── generate_data.php
├── output.sql
├── generalSQL.php

📁 inputs/
├── name_input.txt
├── country_input.txt

📁 assets/
├── images/
└── styles/

Tech Stack
- PHP 8+
- MySQL
- HTML5 / Bootstrap 5
- AMPPS local server

How to Run
1. Clone or download this repository
2. Place it inside your AMPPS `www/` directory
3. Open `localhost/index.php` in your browser
4. Click **"Initialize Database"**
5. Use login credentials from the `USERS` table in MySQL