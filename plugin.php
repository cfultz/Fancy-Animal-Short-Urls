<?php
/*
Plugin Name: Fancy Animal Short Urls
Plugin URI: http://fultz.dev
Description: Short URLs like <tt>https://short.rip/SwiftCrimsonBullfrog</tt>
Version: 0.9
Modifier: CFultz
Modifier URI: https://fultz.dev


Original Plugin Name: Word Based Short Urls
Original Plugin Author: Ozh 
Original Plugin URL: https://github.com/ozh/yourls-word-based-short-urls

Wordlists:
Adjectives: https://github.com/ozh/yourls-word-based-short-urls/blob/master/adjectives.txt
Colors: https://github.com/imsky/wordlists/blob/master/adjectives/colors.txt
Animals: https://github.com/sroberts/wordlists/blob/master/animals.txt
*/

/********** Edit this if you want **************/

// how many words in the shorturl? The first will be adjectives, the second a color, and the last will be a animal
define('CFULTZ_FASU_NUMBER_OF_WORDS', 3);

// adjective list
define('CFULTZ_FASU_ADJECTIVE_LIST', __DIR__.'/adjectives.txt');

// noun list
define('CFULTZ_FASU_COLOR_LIST', __DIR__.'/colors.txt');

// noun list
define('CFULTZ_FASU_ANIMAL_LIST', __DIR__.'/animals.txt');


/********** No touching further **************/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Generate a random AjdectiveAdjectiveNoun
yourls_add_filter('random_keyword', 'cfultz_fasu_random_keyword');
function cfultz_fasu_random_keyword() {

    $adjective  = ucfirst( cfultz_fasu_get_random_word_from_file(CFULTZ_FASU_ADJECTIVE_LIST) );
    $color = ucfirst( cfultz_fasu_get_random_word_from_file(CFULTZ_FASU_COLOR_LIST) );
    $animal = ucfirst( cfultz_fasu_get_random_word_from_file(CFULTZ_FASU_ANIMAL_LIST) );

    return $adjective.$color.$animal;
}

// Don't increment sequential keyword tracker
yourls_add_filter('get_next_decimal', 'cfultz_fasu_keyword_next_decimal');
function cfultz_fasu_keyword_next_decimal($next) {
    return ($next - 1);
}

// Append lowercase and uppercase letters to the currently used charset
yourls_add_filter('get_shorturl_charset', 'cfultz_fasu_charset');
function cfultz_fasu_charset($charset) {
    return $charset.'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
}

/**
 *  Read random line from file
 *
 *  @param $file_to_read    path of file to read
 *  @return string          random line from file, trimmed of \n
 */
function cfultz_fasu_get_random_word_from_file($file_to_read) {
    static $num_of_lines = array();

    $file = new \SplFileObject($file_to_read, 'r');

    // if we haven't already counted the number of lines, count them
    if (!isset($num_of_lines[$file_to_read])) {
        $num_of_lines[$file_to_read] = cfultz_fasu_get_number_of_lines($file_to_read);
    }
    $file->seek( mt_rand(0,$num_of_lines[$file_to_read]) );

    return (trim($file->fgets()));
}

/**
 *  Get total number of lines from file
 *
 *  @param $file_to_read    path of file to read
 *  @return integer         number of lines
 */
function cfultz_fasu_get_number_of_lines($file_to_read) {
    $file = new \SplFileObject($file_to_read, 'r');
    $file->seek(PHP_INT_MAX);
    return ($file->key() + 1);
}
