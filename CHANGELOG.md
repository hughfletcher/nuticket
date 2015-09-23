# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][unreleased]
### Added
- `time_log` table.
- Robofile in default theme and phar Robo.phar added to dev ansible provisioning.
- Integrated [`Caffenated\Modules`](https://github.com/caffeinated/modules) for module support.
- Dev database seeds added.
- Unit tests for `App\Providers\ConfigServiceProvider` and `App\Providers\AppServiceProvider`.
- Added `CHANGELOG.md`.
- Added `LICENSE.txt`.
- Added `readme.md`.
- Added source attribute to User model.
- User is now selected through modal in ticket create.
- Arbertrary users with email can now be added while creating ticket.
- `cached_asset()` added in default theme helper. All references changed to use this.

### Changed
- Updated to Laravel v5.1.4.
- Dev provision now uses directory naming 'nuticket' instead of 'tickets'.
- 'time_spent' column in tickets table is now 'hours'.
- Time is now tracked in 'time_log' table.
- Bower now used in default theme. 
- Assets are now compiled/copied through Robo from bower and no longer in repo.
- Database migrations refactored.
- Entire schema change for 'config' table.
- Fixed responsive table scrollbar.
- Dev vagrant box now uses ubuntu default cloud box.
- Moved `itsgoingd/clockwork` to a `require` composer depencency.
- Minor schema change for 'tickets` table.
- Minor schema change for 'users` table.
- Began reorganizing `app\Repositories`.



### Removed
- Removed 'time_spent' column from ticket_actions table.
- Tracking of time in 'ticket_actions'.
- Removed Orchestra\Memory.
- Removed Orchestra\View.
- tickets.todo removed in place of changelog.md
- Removed all adldap functionality out of core to seperate module.
- `js()` and `css()` functions removed in default theme helpers.

## [0.0.1] - 2015-05-15
### Added
- Tickets with commenting.
- Reporting.
- Active Directory/LDAP authentication.
