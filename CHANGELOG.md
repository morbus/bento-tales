
# Changelog
All notable changes to this project will be documented in this file,
based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Scaffolding

- Begin work on the /awaken command.
- Begin work on the /make command.
- Begin work on the /view command.
- Begin work on the /dream command.
- Add basic database support with PDO.
- Add ext-uv for a more performant loop.
- register.php: Bot commands should not be hardcoded.
- Add ./custom to the repository? With a README? A gitkeep?
  - Add docs on how to add multiple sources for game libraries.
    - $bentoTales->loadAssetsIn(['./src', './custom', './contrib/repository-name']
    - Supports Commands/Ping.php or NAMESPACE/Commands/Ping.php
    - Without being added to the composer.json.
- Set presence for the bot.
- Continue template spelunking.
- The intro should suggest you're retrying existing recipes with aplomb.

## [0.0.1] - 2023-??-??

### Added

- Class-ifying and drop-in autoloading of assets.
- Git repository creation and language scaffolding.
