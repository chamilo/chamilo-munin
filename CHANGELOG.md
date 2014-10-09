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
