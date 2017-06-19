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

ini_set('error_reporting', 'E_ALL & ~E_WARNING & ~E_NOTICE');
$bd = '/var/www';
$sub = '/www';
$connections = get_registrations($bd, $sub);
$output = '';
if (!empty($argv[1]) && $argv[1] == 'config') {
    // Global Munin attr., see http://munin-monitoring.org/wiki/protocol-config
    $output .= "graph_title Chamilo registered users v2\n";
    //$output .= "graph_args --lower-limit 0\n";
    $output .= "graph_args --lower-limit 0 --base 1000 --logarithmic'\n";
    $output .= "graph_category chamilo\n";
    $output .= "graph_info This graph shows the number of registered users on Chamilo portals over time.\n";
    $output .= "graph_vlabel Registered user accounts\n";
    $output .= "graph_scale off\n";
    foreach ($connections as $portal => $num) {
        $warning = 50000;
        $critical = 60000;
        $output .= "portal$portal.label Host $portal\n";
//    $output .= "portal$portal.type DERIVE\n";
        //$output .= "portal$portal.max 500\n";
//    $output .= "portal$portal.min 0\n";
//    $output .= "portal$portal.max 11000\n";
        $output .= "portal$portal.warning $warning\n";
        $output .= "portal$portal.critical $critical\n";
        $output .= "portal$portal.draw LINE2\n";
    }
    file_put_contents('/tmp/get_registered_users_config', $output);
    exit;
}
if (is_array($connections) && count($connections) > 0) {
    foreach ($connections as $portal => $num) {
        $output .= "portal$portal.value $num\n";
    }
}
file_put_contents('/tmp/get_registered_users', $output);

function get_registrations($bd, $sub)
{
    $match_count = 0;
    $connections = array();
    $list = scandir($bd);
    foreach ($list as $dir) {
        //skip system directories
        if (substr($dir, 0, 1) == '.' or $dir == 'lost+found') {
            continue;
        }
        //check the existence of configuration.php
        $config_file = '';
        if (is_file($bd.'/'.$dir.$sub.'/app/config/configuration.php')) {
            // Chamilo 1.10
            $config_file = $bd.'/'.$dir.$sub.'/app/config/configuration.php';
        } elseif (is_file($bd.'/'.$dir.$sub.'/main/inc/conf/configuration.php')) {
            // Chamilo 1.9
            $config_file = $bd.'/'.$dir.$sub.'/main/inc/conf/configuration.php';
        }
        if (!empty($config_file)) {
            $inc = include_once($config_file);
            $dbh = mysqli_connect($_configuration['db_host'], $_configuration['db_user'],
                $_configuration['db_password']);
            if ($inc !== false && $dbh !== false) {
                $db = $_configuration['main_database'];
                $current_date = date('Y-m-d H:i:s', time());
                $user_table = $db.'.user';
                $query = "SELECT count(user_id) ".
                    " FROM ".$user_table;
                //echo $query."\n";
                $res = mysqli_query($dbh, $query);
                if ($res === false) {
                    $num = 0;
                } else {
                    $row = mysqli_fetch_row($res);
                    $num = $row[0];
                }
                //echo sprintf("[%7d]",$num)." users connected to ".$_configuration['root_web']." last $last_connect_minutes'\n";
                $cut_point = 7;
                if (substr($_configuration['root_web'], 0, 5) == 'https') {
                    $cut_point = 8;
                }
                $connections[str_replace('.', '_', substr($_configuration['root_web'], $cut_point, -1))] = $num;
                $match_count += $num;
                mysqli_close($dbh);
            } else {
                //echo "$bd/$dir$sub:could not open configuration.php or database:\n";
            }
        }
    }
    return $connections;
}
