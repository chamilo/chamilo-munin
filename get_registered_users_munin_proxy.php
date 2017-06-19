#!/usr/bin/php
<?php
$doc = <<<CUT
=head1 NAME

chamilo_users - Munin plugin to monitor the number of registered users on Chamilo portals.

=head1 APPLICABLE SYSTEMS

Chamilo 1.* web applications.

=head1 CONFIGURATION

The plugin needs access to the root web directory /var/www (or any other given
path) in order to scan for Chamilo installations. It also offers a way to 
define subdirs if your Chamilo portals are generally stored under two folder
levels inside /var/www/. The plugin also needs the possibility to execute PHP
on the command line (php-cli package) and to connect to a MySQL database
(php5-mysql package).

=head1 INTERPRETATION

The plugin shows the number of registered users at any given time for any
Chamilo portal, indicating the URL of the corresponding portal.

=head1 MAGIC MARKERS

  #%# family=auto
  #%# capabilities=autoconf

=head1 BUGS

None known of

=head1 VERSION

  0.2

=head1 AUTHOR

Yannick Warnier <yannick.warnier@beeznest.com>

=head1 LICENSE

AGPLv3

=cut
CUT;

if (!empty($argv[1]) && $argv[1] == 'config') {
    // Global Munin attr., see http://munin-monitoring.org/wiki/protocol-config
    if (!is_file('/tmp/get_registered_users_config')) {
        @exec(__DIR__.'/get_registered_users_munin.php config');
    }
    readfile('/tmp/get_registered_users_config');
    exit;
}
if (!is_file('/tmp/get_registered_users')) {
    @exec(__DIR__.'/get_registered_users_munin.php');
}
readfile('/tmp/get_registered_users');

