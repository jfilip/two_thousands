<?php
/**
 * Generate a playlist file for JWPlayer based on the sorting parameters from the main page.
 *
 * @author    Justin Filip <jfilip@gmail.com>
 * @copyright 2010 Justin Filip - http://jfilip.ca/
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('db.php');
require_once('lib.php');


$sort = (!empty($_GET['sort']) ? $_GET['sort'] : 'year');
$dir  = (!empty($_GET['dir']) ? $_GET['dir'] : 'ASC');


?>
<feed xmlns='http://www.w3.org/2005/Atom'
    xmlns:media='http://search.yahoo.com/mrss/'
    xmlns:jwplayer='http://developer.longtailvideo.com/trac/wiki/FlashFormats'>
    <title>Top Songs of the Decade Playlist</title>

<?php

track_sort($tracks, $sort, $dir);

foreach ($tracks as $track) {
    $title   = $track['artist'] . ' - ' . $track['title'];
    $genre   = implode(', ', $track['genre']);
    $summary = htmlentities("Album: {$track['album']}\nYear: {$track['year']}\nGenre: $genre");

    $thumbnail = strtolower(str_replace(' ', '_', $track['album']));
    $thumbnail = eregi_replace('[^a-z0-9_]', '', $thumbnail);
    $thumbnail = str_replace('__', '_', $thumbnail);

    if ($thumbnail[0] == '_') {
        $thumbnail = substr($thumbnail, 1);
    }

    if ($thumbnail[strlen($thumbnail) - 1] == '_') {
        $thumbnail = substr($thumbnail, strlen($thumbnail) - 1);
    }

    $thumbnail .= '.png';

    ?>
    <entry>
        <title><?php echo $title; ?></title>
        <link rel="alternate" type="text/html" href="<?php echo $track['url']; ?>" />
        <summary><?php echo $summary; ?></summary>
        <media:group>
            <media:content url="audio.normal/<?php echo $track['filename']; ?>" type="audio/mpeg" duration="<?php echo $track['length']; ?>" />
            <media:thumbnail url="images/thumbs/<?php echo $thumbnail; ?>" />
        </media:group>
    </entry>

<?php

}

?>

</feed>