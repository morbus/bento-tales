
# Changelog
All notable changes to this project will be documented in this file,
based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Scaffolding

- I'm not even sure I like the getInfo() stuff anymore.
  - use attribute properties for this list?
  - name and description getters, but author in attribute? meh.
  - I feel like most anything in here would be better handled as an "unlock".
  - Though, we'd still need the oneliner description for the CommandBuilder.
  - Expand to a package.json-like something?
    - "name": "loidbot",
    - "version": "1.0.0",
    - "author": "Morbus Iff <morbus@disobey.com>",
    - "description": "Land of Idle Demons is an incremental and idle RPG bot for Discord servers.",
    - "homepage": "https://github.com/morbus/loidbot#readme",
- Add logging to each Command for easier debugging?
- Does "Custom assets" in README need a rewrite?
- Create a Character class for our visitors.
- /awaken
  - Db: Create user record and state.
  - Db: Set the next step of the tutorial.
  - Db: Spit out when the next /awaken is.
  - Check awaken level for tutorial?
  - The intro should suggest you're retrying existing recipes with aplomb.
- /make
  - Needs to error if recipe not found.
  - Create isUnlocked() interface for recipes.
  - Check for isUnlocked() in handle().
  - Create new User($guild, $user) and ::getRecipes().
  - Check for isUnlocked() in autocomplete().
  - Replace autocomplete() with getRecipes().
- /dream
  - ???
- /reflect
  - Gets information about player, characters, recipes, etc.

## [0.2.0] - 2025-??-??

### Added

- Support databases with the Doctrine ORM. Lots of refactoring for this:
  - `TalesBot\Attribute\Entity` added to mark Doctrine ORM data entities.
  - `bin/doctrine` added, though we're not really using it for anything yet.
  - `loadAddonsIn()` has been refactored into much smaller parts. Start with
    `loadAddons()` to see the new approach. Theoretically, this will make it
    more flexible for far-future situations with third party custom classes.
    Which will likely never happen, but I'm still going for a LORD IGM feel.

### Changed

- Each command and recipe class are now part of a specific "Addons" directory.
  This allows that directory to include other classes and media, and more
  adequately promotes the idea of grouped resources that can be dropped in as
  a one-location-whole instead of spread throughout the codebase. For example,
  instead of `Command/Awaken.php` and `media/awaken/*.svg`, it's now
  `Addon/Awaken/AwakenCommand.php` and `Addon/Awaken/media/*.svg`.
- `composer run qa` will check `contrib` and `custom` directories too.
- More setup stuff moved from `TalesBot.php` to `register.php` and `start.php`.
- `register.php` and `start.php` replaced with `bin/talesbot`.

## [0.1.0] - 2025-03-05

### Added

- Very dumb first attempts at /awaken and /make.
- Class-ifying and drop-in autoloading of assets.
- Git repository creation and language scaffolding.
