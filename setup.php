#!/usr/bin/php
<?php
/**
 * This script adds links to /etc/munin/plugins/
 * @author Yannick Warnier <yannick.warnier@beeznest.com>
 */
/**
 * Init
 */
$cwd = __DIR__;
$dest = '/etc/munin/plugins';
$cmd = 'ln -s %s %s';
@touch($dest.'/_testchamiloplugin');
if (!is_file($dest.'/_testchamiloplugin')) {
    die('Cannot write in '.$dest."\n");
}
if (is_file($cwd.'/exclusions.dist.conf') && !is_file($cwd.'/exclusions.conf')) {
    @copy($cwd.'/exclusions.dist.conf', $cwd.'/exclusions.conf');
}
$list = scandir(__DIR__);
/**
 * Add links
 */
foreach ($list as $entry) {
    if (substr($entry, 0, 1) == '.' or substr($entry, -10) != '_proxy.php') {
        continue;
    }
    // transform name
    $symlink = preg_replace('/^get_/', 'chamilo-', $entry);
    $symlink = preg_replace('/_/', '-', $symlink);
    $symlink = preg_replace('/-munin-proxy.php$/', '', $symlink);
    exec('ln -s '.__DIR__.'/'.$entry.' '.$dest.'/'.$symlink);
    //echo $entry."-> $symlink\n";
}
unlink($dest.'/_testchamiloplugin');
echo "Links successfully created in $dest.\n";
