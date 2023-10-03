
# Changelog
All notable changes to this project will be documented in this file,
based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Scaffolding

- Add basic database support with PDO.
- Create a log of commands to examine for bugs?
- When there's more than 25 autocomplete results, add substring searching.
- We need some sort of lookup list that handles case-insensitive searching.
- /awaken
  - Check awaken level?
  - Db: Create user record and state.
  - Db: Set the next step of the tutorial.
  - Db: Spit out when the next /awaken is.
  - The intro should suggest you're retrying existing recipes with aplomb.
- /make
  - Create isUnlocked() interface for recipes.
  - Create new User($guild, $user) and getRecipes().
- /dream
  - ???
- /view
  - ???

## [0.0.1] - 2023-??-??

### Added

- Class-ifying and drop-in autoloading of assets.
- Git repository creation and language scaffolding.
