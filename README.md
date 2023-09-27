
# Bento Tales

_A Discord bot to make good food and hear good stories._

## Installation

1. Run `composer install` to get all the PHP dependencies.
2. Copy `.env.example` to `.env`, then edit `.env` with your details.
3. To register the bot's application commands, run `composer bot:register`.
4. To start the bot, run `composer bot:start`.

## OAuth2 URL Generator

The bot needs the following scopes and permissions:

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

## Developers

1. Run `composer:qa` to lint everything.

### Custom assets

In addition to the shipped assets available in `/src/TalesBot/Commands`,
`/src/TalesBot/Recipes`, and other core directories, you can define your own
custom assets outside of these locations. You can think of these locations
as if they're "custom mods" to the TalesBot framework. To get started, either
add your asset classes in `./custom`, or specify your own (and/or additional)
directories by adding them to the `loadAssetsIn()` array in `start.php`.
You'll need to make sure the class `namespace` maps to the directory structure,
otherwise Talesbot will be unable to load your asset properly.

Here's an example of how you'd organize and namespace custom assets:

| Location                                           | Namespace                                        |
|----------------------------------------------------|--------------------------------------------------|
| `custom/Commands/Example1.php`                     | `namespace Commands`                             |
| `custom/MyMod/Commands/Example2.php`               | `namespace MyMod\Commands`                       |
| `contrib/theirRepo/theirMod/Commands/Example3.php` | `namespace theirMod\Commands` ***<sup>1</sup>*** |

<sup>1</sup> Only possible if you pass `contrib/theirRepo` to `loadAssetsIn()`.
