
# Changelog
All notable changes to this project will be documented in this file,
based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Scaffolding

- Move from custom hooks to event listeners and promises?
  - Call $this->emit on the Discord instance to issue events.
  - But how would we get code in, say, a Command to listen to them?
- Create a Character class for our visitors.
- Add basic database support with PDO.
- We need some sort of lookup list that handles case-insensitive searching.
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
  - When there's more than 25 autocompletes, add substring searching.
- /dream
  - ???
- /view
  - ???

## [0.2.0] - 2025-??-??

### Added

### Changed

- Each individual command and recipe are now stored in their own directories.
  This allows that directory to include other related classes and media, and
  more adequately promotes the idea of packages/addons that can be dropped in
  as a one-location-whole instead of spread throughout the codebase. For example,
  instead of `Commands/Awaken.php` and `media/awaken/*.svg`, it's now
  `Commands/Awaken/Awaken.php` and `Commands/Awaken/media/*.svg`.

## [0.1.0] - 2025-03-05

### Added

- Very dumb first attempts at /awaken and /make.
- Class-ifying and drop-in autoloading of assets.
- Git repository creation and language scaffolding.
