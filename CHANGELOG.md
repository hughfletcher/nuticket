# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][unreleased]
### Added
- `time_log` table.
- Elixer gulp file now default theme.
- Integrated [`Caffenated\Modules`](https://github.com/caffeinated/modules) for module support.
- Dev database seeds added.
- Unit tests for `App\Providers\ConfigServiceProvider` and `App\Providers\AppServiceProvider`.
- Added `CHANGELOG.md`.
- Added `LICENSE.txt`.

### Changed
- Updated to Laravel v5.1.4.
- Dev provision now uses directory naming 'nuticket' instead of 'tickets'.
- 'time_spent' column in tickets table is now 'hours'.
- Time is now tracked in 'time_log'.
- Bower now used in default theme. 
- Assets are now compiled/copied through elixer from bower and no longer in repo.
- Database migrations refactored.
- Entire schema change for 'config' table.

### Removed
- Removed 'time_spent' column from ticket_actions table.
- Tracking of time in 'ticket_actions'.
- Removed Orchestra\Memory.
- Removed Orchestra\View.
- tickets.todo removed in place of changelog.md

## [0.0.1] - 2015-05-15
### Added
- Tickets with commenting.
- Reporting.
- Active Directory/LDAP authentication.
