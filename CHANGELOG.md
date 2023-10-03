
# Changelog
All notable changes to this project will be documented in this file,
based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Scaffolding

- Add basic database support with PDO.
- Create a log of commands to examine for bugs?
- We need some sort of lookup list that handles case-insensitive searching.
- Should we move the tutorial stuff into a single class for ease-of-finding?
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

## [0.0.1] - 2023-??-??

### Added

- Class-ifying and drop-in autoloading of assets.
- Git repository creation and language scaffolding.
