<?php
/**
 * Library of functions used for this site.
 *
 * @author    Justin Filip <jfilip@gmail.com>
 * @copyright 2010 Justin Filip - http://jfilip.ca/
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


define('PLAYER_WIDTH',  '800');
define('PLAYER_HEIGHT', '332');


/**
 * Print a standard page header.
 *
 * @param none
 * @return HTML output.
 */
function page_header() {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/' .
         'xhtml1-strict.dtd">' . "\n";
    echo '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">' . "\n";
    echo '    <head>' . "\n";
    echo '        <title>two thousands</title>' . "\n";
    echo '        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n";
    echo '        <link type="text/css" rel="stylesheet" media="screen" href="style.css" />' . "\n";
    echo '        <script type="text/javascript" src="lib.js"></script>' . "\n";
    echo '    </head>' . "\n";
    echo '    <body>' . "\n";
}

/**
 * Print a standard page footer.
 *
 * @param none
 * @return HTML output.
 */
function page_footer() {
    echo '        <div class="footer">' . "\n";
    echo '            <p>Media player: <a href="http://developer.longtailvideo.com/trac/">JW Player</a>' .
         ' | | | | Background image: <a href="http://www.colourlovers.com/pattern/716548/Blue_Flame">' .
         'Blue Flame by miice</a></p>' . "\n";
    echo '        </div>' . "\n";

    echo '    </body>' . "\n";
    echo '</html>' . "\n";
}

/**
 * Get the album art thumbnail name based on the track information
 *
 * @param array $track Track information.
 * @return string The filename of the album thumbnail image.
 */
function thumbnail_get_name($track) {
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

    return $thumbnail;
}

/**
 * Print out the heading row for the main table, including context-sensitive links for sorting each column.
 *
 * @param string $sort The field currently being used to sort the table.
 * @param string $dir  The current direction being used to sort the table.
 * @return HTML output.
 */
function print_heading_row($sort, $dir) {
    echo '            <tr>' . "\n";

    $headers = array(
        'artist' => 'Artist',
        'title'  => 'Title',
        'album'  => 'Album',
        'genre'  => 'Genre',
        'year'   => 'Year'
    );

    foreach ($headers as $field => $title) {
        echo '                <th class="listheader">';
        echo '<a href="index.php?sort=' . $field . '&amp;dir=';

        if ($sort == $field) {
            if ($dir == 'ASC') {
                echo 'DESC';
            } else if ($dir == 'DESC') {
                echo 'ASC';
            }
        } else {
            echo 'ASC';
        }

        echo '">' . $title . '</a>';
        echo '</th>' . "\n";
    }

    echo '            </tr>' . "\n";
}

/**
 * Sort the track list based on the supplied field and direction.
 *
 * @param array $tracks     Refernce to the array of tracks.
 * @param string $field     The field to sort.
 * @param string $direction The direction to sort in.
 * @return bool True on success, False otherwise.
 */
function track_sort(&$tracks, $field, $direction) {
    switch ($direction) {
        case 'ASC':
        case 'DESC':
            break;

        default:
            return false;
    }

    switch ($field) {
        case 'artist':
        case 'title':
        case 'album':
            if ($direction == 'ASC') {
                return usort($tracks, create_function(
                        '$a, $b',
                        'return strcmp($a[\'' . $field . '\'], $b[\'' . $field . '\']);'
                    )
                );
            } else if ($direction == 'DESC') {
                return usort($tracks, create_function(
                        '$a, $b',
                        'return -strcmp($a[\'' . $field . '\'], $b[\'' . $field . '\']);'
                    )
                );
            }

            break;

        case 'genre':
            if ($direction == 'ASC') {
                return usort($tracks, create_function(
                        '$a, $b',
                        '$ca = current($a[\'' . $field . '\']);
                         $cb = current($b[\'' . $field . '\']);
                         return strcmp($ca, $cb);'
                    )
                );
            } else if ($direction == 'DESC') {
                return usort($tracks, create_function(
                        '$a, $b',
                        '$ca = current($a[\'' . $field . '\']);
                         $cb = current($b[\'' . $field . '\']);
                         return -strcmp($ca, $cb);'
                    )
                );
            }

            break;

        case 'year':
            if ($direction == 'ASC') {
                return usort($tracks, create_function(
                        '$a, $b',
                        'if ($a[\'datestamp\'] == $b[\'datestamp\']) {
                            return 0;
                         }
                         return ($a[\'datestamp\'] < $b[\'datestamp\']) ? -1 : 1;'
                    )
                );
            } else if ($direction == 'DESC') {
                return usort($tracks, create_function(
                        '$a, $b',
                        'if ($a[\'datestamp\'] == $b[\'datestamp\']) {
                            return 0;
                         }
                         return ($a[\'datestamp\'] > $b[\'datestamp\']) ? -1 : 1;'
                    )
                );
            }

            break;

        default:
            return false;
    }
}

/**
 * Borrowed code from php.net
 * simple task: convert everything from UTF-8 into an NCR[numeric character reference]
 *
 * @see http://www.php.net/manual/en/function.htmlentities.php#92105
 */
class unicode_replace_entities {
    public function UTF8entities($content="") {
        $contents = $this->unicode_string_to_array($content);
        $swap = "";
        $iCount = count($contents);
        for ($o=0;$o<$iCount;$o++) {
            $contents[$o] = $this->unicode_entity_replace($contents[$o]);
            $swap .= $contents[$o];
        }
        return mb_convert_encoding($swap,"UTF-8"); //not really necessary, but why not.
    }

    public function unicode_string_to_array( $string ) { //adjwilli
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr( $string, 0, 1, "UTF-8" );
            $string = mb_substr( $string, 1, $strlen, "UTF-8" );
            $strlen = mb_strlen( $string );
        }
        return $array;
    }

    public function unicode_entity_replace($c) { //m. perez
        $h = ord($c{0});
        if ($h <= 0x7F) {
            return $c;
        } else if ($h < 0xC2) {
            return $c;
        }

        if ($h <= 0xDF) {
            $h = ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
            $h = "&#" . $h . ";";
            return $h;
        } else if ($h <= 0xEF) {
            $h = ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6 | (ord($c{2}) & 0x3F);
            $h = "&#" . $h . ";";
            return $h;
        } else if ($h <= 0xF4) {
            $h = ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12 | (ord($c{2}) & 0x3F) << 6 | (ord($c{3}) & 0x3F);
            $h = "&#" . $h . ";";
            return $h;
        }
    }
}
