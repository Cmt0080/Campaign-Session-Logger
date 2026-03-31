# Campaign Session Logger
A D&D Campaign Management Tool — Built with PHP, HTML/CSS, and JSON

## What It Is
A web-based tool for Dungeons & Dragons groups to organize and review their tabletop campaign. The DM can log session recaps, track NPCs, and record loot. Players can sign up, log in, and browse the campaign journal to stay up to date on the story.

## Features
- **Role-based access** — DM and Player accounts with different permissions
- **Session Log** — DM logs session recaps, players can browse them in reverse chronological order
- **NPC Tracker** — DM adds NPCs encountered, players view read-only
- **Loot Log** — DM tracks items found, players view read-only
- **DM Notes** — Private notes only visible to the DM (hidden from players entirely)
- **Access control** — If a player tries to access DM Notes directly via URL, they are redirected with an access denied message

## Tech Stack
- **Frontend:** HTML, CSS
- **Backend:** PHP
- **Storage:** JSON files (no database required)

## How to Run
1. Install [XAMPP](https://www.apachefriends.org/)
2. Clone or download this project into your `xampp/htdocs` folder
3. Start **Apache** in the XAMPP Control Panel
4. Open your browser and go to `http://localhost/Campaign-Logger`

## Demo Login
See `DM LOGIN.txt` in the project root for demo DM credentials.
To test a player account, sign up on the Sign Up page and select the "Player" role.

## Pages
| Page | Access |
|------|--------|
| Home | All logged-in users |
| Session Log | All logged-in users |
| NPC Tracker | All logged-in users |
| Loot Log | All logged-in users |
| DM Notes | DM only |
