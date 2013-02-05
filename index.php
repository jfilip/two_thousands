<?php
/**
 * Display the JWPlayer and the table of tracks with sorting capabilities.
 *
 * @author    Justin Filip <jfilip@gmail.com>
 * @copyright 2010 Justin Filip - http://jfilip.ca/
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('lib.php');
require_once('db.php');


$sort = (!empty($_GET['sort']) ? $_GET['sort'] : 'year');
$dir  = (!empty($_GET['dir']) ? $_GET['dir'] : 'ASC');


page_header();

echo '        <div class="instructions">' . "\n";
echo '            <fieldset>' . "\n";
echo '                <legend>Explanation &amp; Instructions</legend>' . "\n";
echo '                <p><b>UPDATE: The source audio files are avaialble for download below and you can download a ' .
      'playlist file for use with them based on the sorting from this page.  Just put the downloaded playlist file ' .
      'into the <i>two_thousands</i> directory contining the audio files and then load that m3u file into your ' .
      'media player of choice.</b></p>';
echo '                <p>This represents quite a bit of work on my part.  I spent a couple months going back ' .
     'through all of the music that was important to me over the last decade and came up with this list of tracks ' .
     'that I feel is a fairly accurate representation of my favourite tracks from the years 2000-2009, inclusive.</p>' . "\n";
echo '                <p>There are most certainly going to be things missing that should probably be here and I am ' .
     'sure that in the coming weeks I\'ll hear all about the glaring omissions I have made from various people but ' .
     'at some point I had to stop choosing and actually get around to building this site.</p>' . "\n";
echo '                <p>Click on the title of a column to sort the table by that column (clicking a column ' .
     'multiple times will change the direction you are sorting by).  The sorting of the table also affects the ' .
     'sorting of the playlist in the flash player below.</p>' . "\n";
echo '                <p>Clicking anywhere in a row in the table will expand that row to display more information ' .
     'and an image of the album / single cover art as well as a link to detailed information about the release.  ' .
     'Clicking anywhere within that expanded row will shrink the row back to it\'s smaller size.</p>' . "\n";
echo '                <div class="right"><p>Thanks for visiting and I hope you enjoy - Justin</p></div>';
echo '            </fieldset>' . "\n";
echo '        </div>' . "\n";

$file = 'playlist.php?sort=' . $sort . '&dir=' . $dir;

$flashvars = 'playlistfile=' . urlencode($file) . '&amp;playlist=right&amp;repeat=list&amp;playlistsize=500' .
             '&amp;width=' . PLAYER_WIDTH . '&amp;volume=50&amp;skin=skins/beelden/beelden.xml';

echo '        <div class="center">' . "\n";
echo '            <div class="player">' . "\n";
echo '                <object height="' . PLAYER_HEIGHT . '" width="' . PLAYER_WIDTH . '" name="player" classid="' .
     'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="player">' . "\n";
echo '                    <param value="player-viral.swf" name="movie">' . "\n";
echo '                    <param value="false" name="allowfullscreen">' . "\n";
echo '                    <param value="always" name="allowscriptaccess">' . "\n";
echo '                    <param value="' . $flashvars . '" name="flashvars">' . "\n";
echo '                    <embed height="' . PLAYER_HEIGHT . '" width="' . PLAYER_WIDTH . '" flashvars="' . $flashvars .
     '" allowfullscreen="false" allowscriptaccess="always" src="player-viral.swf" name="player2" id="player2" ' .
     'type="application/x-shockwave-flash">' . "\n";
echo '                </object>' . "\n";
echo '            </div>' . "\n";
echo '        </div>' . "\n";

echo '        <table class="tracklist" cellspacing="0">' . "\n";
echo '          <tr class="m3udownload"><td colspan="5" class="center">' . "\n";
echo '              <p>Download the source audio files from <a href="http://dl.dropbox.com/u/2179246/two_thousands.zip' .
     '">this link</a>.</p>' . "\n";
echo '              <p><a href="m3u.php?sort=' . $sort . '&dir=' . $dir . '">' .
     'Download a playlist for the MP3s on your computer based on the current sorting</a></p>' . "\n";
echo '</td></tr>' . "\n";

print_heading_row($sort, $dir);

track_sort($tracks, $sort, $dir);

$i = 1;

foreach ($tracks as $track) {
    $thumbnail = thumbnail_get_name($track);

    $oUnicodeReplace = new unicode_replace_entities();
    $artist = $oUnicodeReplace->UTF8entities($track['artist']);
    $title  = $oUnicodeReplace->UTF8entities($track['title']);
    $album  = $oUnicodeReplace->UTF8entities($track['album']);

    $attrs = 'class="smalllistrow"' . ($i % 2 == 0 ? 'id="odd"' : '') . ' onclick="bigitup(' . $i . ');"';

    echo '            <tr name="track' . $i . '" id="small' . $i . '">' . "\n";
    echo '                <td ' . $attrs . '>' . $artist . '</td>' . "\n";
    echo '                <td ' . $attrs . '>' . $title . '</td>' . "\n";
    echo '                <td ' . $attrs . '>' . $album . '</td>' . "\n";
    echo '                <td ' . $attrs . '>' . implode(', ', $track['genre']) . '</td>' . "\n";
    echo '                <td ' . $attrs . '>' . $track['year'] . '</td>' . "\n";
    echo '            </tr>' . "\n";

    echo '            <tr name="track' . $i . '" id="large' . $i . '" class="hidden">' . "\n";
    echo '                <td class="largelistrow" onclick="bigitup(' . $i . ', true);" align="right" colspan="2">' . "\n";
    echo '                    <img src="images/thumbs/' . $thumbnail . '" class="coverart" />' . "\n";
    echo '                </td>' . "\n";
    echo '                <td id="summary" class="largelistrow" onclick="bigitup(' . $i . ', true);" colspan="3">' . "\n";
    echo '            ' . $artist . ' - ' . $title . '<br />' . $album . ' (' . $track['year'] . ')<br />' .
         implode(', ', $track['genre']) . '<br /><br /><a href="' . $track['url'] . '" target="release_info">Release information</a>' . "\n";
    echo '                </td>' . "\n";
    echo '            </tr>' . "\n";

    $i++;
}

echo '        </table>' . "\n";

page_footer();

// BG: http://www.colourlovers.com/pattern/716548/Blue_Flame
