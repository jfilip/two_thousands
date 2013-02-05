<?php
/**
 * Generate an M3U playlist based on the sorting parameters from the main page.
 *
 * @author    Justin Filip <jfilip@gmail.com>
 * @copyright 2010 Justin Filip - http://jfilip.ca/
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('db.php');
require_once('lib.php');


$sort = (!empty($_GET['sort']) ? $_GET['sort'] : 'year');
$dir  = (!empty($_GET['dir']) ? $_GET['dir'] : 'ASC');

// For IE compatibiltiy.
if (ini_get('zlib.output_compression')) {
    ini_set('zlib.output_compression', 'Off');
}

track_sort($tracks, $sort, $dir);

$m3ustring = '# Sorting by ' . $sort . ' ' . $dir . "\n";

foreach ($tracks as $track) {
    $m3ustring .= $track['filename'] . "\n";
}

@header('Content-Disposition: attachment; filename="two_thousands.m3u"');
@header('Content-Type: application/x-winamp-playlist');
@header('Content-Length: ' . strlen($m3ustring));

echo $m3ustring;
