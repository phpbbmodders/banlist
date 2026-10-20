# Ban List

[![Tests](https://github.com/phpbbmodders/banlist/actions/workflows/tests.yml/badge.svg)](https://github.com/phpbbmodders/banlist/actions/workflows/tests.yml) [![Lint](https://github.com/phpbbmodders/banlist/actions/workflows/lint.yml/badge.svg)](https://github.com/phpbbmodders/banlist/actions/workflows/lint.yml)

Adds a page listing currently banned users.

> **This extension has moved into [Ban Hammer](https://github.com/phpbbmodders/ban-hammer).** Ban Hammer now has the ban list as an ACP setting, working on phpBB 3.3 and 4.0. This repository is archived and no longer updated. If you used Ban List, disable and delete it, then install Ban Hammer, turn on **Show the ban list** in its ACP settings, and give groups the *Can view the ban list* permission (the standard and full user roles have it).

## Features

- A **Ban list** link in the header navigation, for users with the *Can view ban list* permission.
- Lists active bans on usernames: user, ban start, ban end (or *Permanent*), and the ban reason.
- Shows each banned user's warning count and last warning date.
- Paginated using the board's *users per page* setting.

## Requirements

- phpBB 3.3.19 or later
- PHP 8.0 or later

## Installation

1. Copy the extension to `/ext/phpbbmodders/banlist`
2. In the Administration Control Panel, go to **Customise → Manage extensions**
3. Enable the **Ban List** extension
4. Give the groups that should see the list the *Can view ban list* permission

### Update instructions

1. Go to your phpBB board > Administration Control Panel > Customise > Manage extensions > Ban List: disable
2. Delete all files of the extension from `/ext/phpbbmodders/banlist`
3. Upload all the new files to the same locations
4. Go to your phpBB board > Administration Control Panel > Customise > Manage extensions > Ban List: enable
5. Purge the board cache

## TODO

Ideas not yet built, practical and speculative alike: [`docs/TODO.md`](docs/TODO.md).

## Contributing

Contributions are welcome!

- **Bug reports**: [Open an issue](https://github.com/phpbbmodders/banlist/issues).
- **Everything else** (questions, feature requests, ideas, general discussion): [Use Discussions](https://github.com/orgs/phpbbmodders/discussions), or the [community forum](https://www.phpbbmodders.com/community/).
- Pull requests are welcome for bug fixes or discussed features.

## Acknowledgments

- Code review, bug fixes, and documentation assisted by [Claude](https://www.anthropic.com/claude).

## License

This extension is licensed under the **GNU General Public License v2.0**.

See [license.txt](license.txt) for more information.
