# TODO

This extension has moved into [Ban Hammer](https://github.com/phpbbmodders/ban-hammer); the idea below is now planned in [Ban Hammer's TODO](https://github.com/phpbbmodders/ban-hammer/blob/master/docs/TODO.md).

## Export and import of bans

Idea, not yet scoped. Requested on phpBB.com in [Export/Import banned email list](https://www.phpbb.com/community/viewtopic.php?t=2669812) (03/24/2026): someone who runs several boards wants ACP buttons to export banned emails from one board and import them on the others.

- Today the only way is a manual SQL query on `phpbb_banlist` in phpMyAdmin, then pasting the result into the ACP email ban page.
- Could cover usernames and IP addresses as well as emails.
- phpBB 4.0 stores bans differently from 3.3, so the export format and import code must work on both.
- Decide whether this belongs in this extension (which today only shows a public list of banned users) or in a separate one.
