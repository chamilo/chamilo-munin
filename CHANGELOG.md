Chamilo Munin 2.1 (20170619)
============================
* Use PDO for database connections to avoid issues with PHP 7.1

Chamilo Munin 2.0 (20160714)
============================
* Update for Chamilo 1.10 and PHP 7

Chamilo Munin 0.6 (20141009)
============================
* Fix issue with script name in new feature of get_connected_users_x

Chamilo Munin 0.5 (20141009)
============================
* Add mechanism to guess number of minutes from the script name in get_connected_users

Chamilo Munin 0.4 (20141009)
============================
* Fix proxy scripts to load relative path if file not found
* Add flexibility to copy connected_users script for different time versions
* Changed default connected_users time window to 5 minutes (instead of 1)

Backwards incompatible changes
------------------------------
* Change in connected_users script requires a change in symbolic link in /etc/munin/plugins to add number of minutes (5 by default)

Chamilo Munin 0.3
=================
* Upload to Github
* Add more charts
* Document suggested install procedure

Chamilo Munin 0.2
=================
* Add proxy scripts to avoid computing results on the fly

Chamilo Munin 0.1
=================
* Initial scripts creation
