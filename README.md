
# Bento Tales

_A Discord bot to make good food and hear good stories._

## Installation

1. Run `composer install` to get all the PHP dependencies.
2. Copy `.env.example` to `.env`, then edit `.env` with your details.
3. To register the bot's application commands, run `composer bot:register`.
4. To start the bot, run `composer bot:start`.

## OAuth2 URL Generator

The Bento Tales bot needs the following scopes and permissions:

- Scopes
  - applications.commands
  - bot
- Bot Permissions
  - General Permissions
    - Read Messages/View Channels
  - Text Permissions
    - Send Messages
    - Manage Messages
    - Embed Links
    - Read Message History
    - Add Reactions
    - Use Slash Commands
