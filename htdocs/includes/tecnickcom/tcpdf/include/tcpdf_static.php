<?php

// File name   : tcpdf_static.php
// Version     : 1.1.3
// Begin       : 2002-08-03
// Last Update : 2015-04-28
// Author      : Nicola Asuni - Tecnick.com LTD - www.tecnick.com - info@tecnick.com
// License     : GNU-LGPL v3 (http://www.gnu.org/copyleft/lesser.html)

// Copyright (C) 2002-2015 Nicola Asuni - Tecnick.com LTD

// This file is part of TCPDF software library.

// TCPDF is free software: you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.

// TCPDF is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU Lesser General Public License for more details.

// You should have received a copy of the License
// along with TCPDF. If not, see


// See LICENSE.TXT file for more information.


// Description :
//   Static methods used by the TCPDF class.



/**
 * @file
 * This is a PHP class that contains static methods for the TCPDF class.<br>
 * @package com.tecnick.tcpdf
 * @author Nicola Asuni
 * @version 1.1.2
 */

/**
 * @class TCPDF_STATIC
 * Static methods used by the TCPDF class.
 * @package com.tecnick.tcpdf
 * @brief PHP class for generating PDF documents without requiring external extensions.
 * @version 1.1.1
 * @author Nicola Asuni - info@tecnick.com
 */
class TCPDF_STATIC {

	/**
	 * Current TCPDF version.
	 * @private static
	 */
	private static $tcpdf_version = '6.3.2';

	/**
	 * String alias for total number of pages.
	 * @public static
	 */
	public static $alias_tot_pages = '{:ptp:}';

	/**
	 * String alias for page number.
	 * @public static
	 */
	public static $alias_num_page = '{:pnp:}';

	/**
	 * String alias for total number of pages in a single group.
	 * @public static
	 */
	public static $alias_group_tot_pages = '{:ptg:}';

	/**
	 * String alias for group page number.
	 * @public static
	 */
	public static $alias_group_num_page = '{:png:}';

	/**
	 * String alias for right shift compensation used to correctly align page numbers on the right.
	 * @public static
	 */
	public static $alias_right_shift = '{rsc:';

	/**
	 * Encryption padding string.
	 * @public static
	 */
	public static $enc_padding = "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A";

	/**
	 * ByteRange placemark used during digital signature process.
	 * @since 4.6.028 (2009-08-25)
	 * @public static
	 */
	public static $byterange_string = '/ByteRange[0 ********** ********** **********]';

	/**
	 * Array page boxes names
	 * @public static
	 */
	public static $pageboxes = array('MediaBox', 'CropBox', 'BleedBox', 'TrimBox', 'ArtBox');

	

	/**
	 * Return the current TCPDF version.
	 * @return TCPDF version string
	 * @since 5.9.012 (2010-11-10)
	 * @public static
	 */
	public static function getTCPDFVersion() {
		return self::$tcpdf_version;
	}

	/**
	 * Return the current TCPDF producer.
	 * @return TCPDF producer string
	 * @since 6.0.000 (2013-03-16)
	 * @public static
	 */
	public static function getTCPDFProducer() {
		return "\x54\x43\x50\x44\x46\x20".self::getTCPDFVersion()."\x20\x28\x68\x74\x74\x70\x3a\x2f\x2f\x77\x77\x77\x2e\x74\x63\x70\x64\x66\x2e\x6f\x72\x67\x29";
	}

	/**
	 * Sets the current active configuration setting of magic_quotes_runtime (if the set_magic_quotes_runtime function exist)
	 * @param $mqr (boolean) FALSE for off, TRUE for on.
	 * @since 4.6.025 (2009-08-17)
	 * @public static
	 */
	public static function set_mqr($mqr) {
		if (!defined('PHP_VERSION_ID')) {
			$version = PHP_VERSION;
			define('PHP_VERSION_ID', (($version[0] * 10000) + ($version[2] * 100) + $version[4]));
		}
		if (PHP_VERSION_ID < 50300) {
			@set_magic_quotes_runtime($mqr);
		}
	}

	/**
	 * Gets the current active configuration setting of magic_quotes_runtime (if the get_magic_quotes_runtime function exist)
	 * @return Returns 0 if magic quotes runtime is off or get_magic_quotes_runtime doesn't exist, 1 otherwise.
	 * @since 4.6.025 (2009-08-17)
	 * @public static
	 */
	public static function get_mqr() {
		if (!defined('PHP_VERSION_ID')) {
			$version = PHP_VERSION;
			define('PHP_VERSION_ID', (($version[0] * 10000) + ($version[2] * 100) + $version[4]));
		}
		if (PHP_VERSION_ID < 50300) {
			return @get_magic_quotes_runtime();
		}
		return 0;
	}

	/**
	 * Check if the URL exist.
	 * @param $url (string) URL to check.
	 * @return Boolean true if the URl exist, false otherwise.
	 * @since 5.9.204 (2013-01-28)
	 * @public static
	 */
	public static function isValidURL($url) {
		$headers = @get_headers($url);
    	return (strpos($headers[0], '200') !== false);
	}

	/**
	 * Removes SHY characters from text.
	 * Unicode Data:<ul>
	 * <li>Name : SOFT HYPHEN, commonly abbreviated as SHY</li>
	 * <li>HTML Entity (decimal): "&amp;#173;"</li>
	 * <li>HTML Entity (hex): "&amp;#xad;"</li>
	 * <li>HTML Entity (named): "&amp;shy;"</li>
	 * <li>How to type in Microsoft Windows: [Alt +00AD] or [Alt 0173]</li>
	 * <li>UTF-8 (hex): 0xC2 0xAD (c2ad)</li>
	 * <li>UTF-8 character: chr(194).chr(173)</li>
	 * </ul>
	 * @param $txt (string) input string
	 * @param $unicode (boolean) True if we are in unicode mode, false otherwise.
	 * @return string without SHY characters.
	 * @since (4.5.019) 2009-02-28
	 * @public static
	 */
	public static function removeSHY($txt='', $unicode=true) {
		$txt = preg_replace('/([\\xc2]{1}[\\xad]{1})/', '', $txt);
		if (!$unicode) {
			$txt = preg_replace('/([\\xad]{1})/', '', $txt);
		}
		return $txt;
	}


	/**
	 * Get the border mode accounting for multicell position (opens bottom side of multicell crossing pages)
	 * @param $brd (mixed) Indicates if borders must be drawn around the cell block. The value can be a number:<ul><li>0: no border (default)</li><li>1: frame</li></ul>or a string containing some or all of the following characters (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B: bottom</li></ul> or an array of line styles for each border group: array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
	 * @param $position (string) multicell position: 'start', 'middle', 'end'
	 * @param $opencell (boolean) True when the cell is left open at the page bottom, false otherwise.
	 * @return border mode array
	 * @since 4.4.002 (2008-12-09)
	 * @public static
	 */
	public static function getBorderMode($brd, $position='start', $opencell=true) {
		if ((!$opencell) OR empty($brd)) {
			return $brd;
		}
		if ($brd == 1) {
			$brd = 'LTRB';
		}
		if (is_string($brd)) {
			// convert string to array
			$slen = strlen($brd);
			$newbrd = array();
			for ($i = 0; $i < $slen; ++$i) {
				$newbrd[$brd[$i]] = array('cap' => 'square', 'join' => 'miter');
			}
			$brd = $newbrd;
		}
		foreach ($brd as $border => $style) {
			switch ($position) {
				case 'start': {
					if (strpos($border, 'B') !== false) {
						// remove bottom line
						$newkey = str_replace('B', '', $border);
						if (strlen($newkey) > 0) {
							$brd[$newkey] = $style;
						}
						unset($brd[$border]);
					}
					break;
				}
				case 'middle': {
					if (strpos($border, 'B') !== false) {
						// remove bottom line
						$newkey = str_replace('B', '', $border);
						if (strlen($newkey) > 0) {
							$brd[$newkey] = $style;
						}
						unset($brd[$border]);
						$border = $newkey;
					}
					if (strpos($border, 'T') !== false) {
						// remove bottom line
						$newkey = str_replace('T', '', $border);
						if (strlen($newkey) > 0) {
							$brd[$newkey] = $style;
						}
						unset($brd[$border]);
					}
					break;
				}
				case 'end': {
					if (strpos($border, 'T') !== false) {
						// remove bottom line
						$newkey = str_replace('T', '', $border);
						if (strlen($newkey) > 0) {
							$brd[$newkey] = $style;
						}
						unset($brd[$border]);
					}
					break;
				}
			}
		}
		return $brd;
	}

	/**
	 * Determine whether a string is empty.
	 * @param $str (string) string to be checked
	 * @return boolean true if string is empty
	 * @since 4.5.044 (2009-04-16)
	 * @public static
	 */
	public static function empty_string($str) {
		return (is_null($str) OR (is_string($str) AND (strlen($str) == 0)));
	}

	/**
	 * Returns a temporary filename for caching object on filesystem.
	 * @param $type (string) Type of file (name of the subdir on the tcpdf cache folder).
	 * @param $file_id (string) TCPDF file_id.
	 * @return string filename.
	 * @since 4.5.000 (2008-12-31)
	 * @public static
	 */
	public static function getObjFilename($type='tmp', $file_id='') {
		return tempnam(K_PATH_CACHE, '__tcpdf_'.$file_id.'_'.$type.'_'.md5(TCPDF_STATIC::getRandomSeed()).'_');
	}

	/**
	 * Add "\" before "\", "(" and ")"
	 * @param $s (string) string to escape.
	 * @return string escaped string.
	 * @public static
	 */
	public static function _escape($s) {
		// the chr(13) substitution fixes the Bugs item #1421290.
		return strtr($s, array(')' => '\\)', '(' => '\\(', '\\' => '\\\\', chr(13) => '\r'));
	}

	/**
	* Escape some special characters (&lt; &gt; &amp;) for XML output.
	* @param $str (string) Input string to convert.
	* @return converted string
	* @since 5.9.121 (2011-09-28)
	 * @public static
	 */
	public static function _escapeXML($str) {
		$replaceTable = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');
		$str = strtr($str, $replaceTable);
		return $str;
	}

	/**
	 * Creates a copy of a class object
	 * @param $object (object) class object to be cloned
	 * @return cloned object
	 * @since 4.5.029 (2009-03-19)
	 * @public static
	 */
	public static function objclone($object) {
		if (($object instanceof Imagick) AND (version_compare(phpversion('imagick'), '3.0.1') !== 1)) {
			// on the versions after 3.0.1 the clone() method was deprecated in favour of clone keyword
			return @$object->clone();
		}
		return @clone($object);
	}

	/**
	 * Output input data and compress it if possible.
	 * @param $data (string) Data to output.
	 * @param $length (int) Data length in bytes.
	 * @since 5.9.086
	 * @public static
	 */
	public static function sendOutputData($data, $length) {
		if (!isset($_SERVER['HTTP_ACCEPT_ENCODING']) OR empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
			// the content length may vary if the server is using compression
			header('Content-Length: '.$length);
		}
		echo $data;
	}

	/**
	 * Replace page number aliases with number.
	 * @param $page (string) Page content.
	 * @param $replace (array) Array of replacements (array keys are replacement strings, values are alias arrays).
	 * @param $diff (int) If passed, this will be set to the total char number difference between alias and replacements.
	 * @return replaced page content and updated $diff parameter as array.
	 * @public static
	 */
	public static function replacePageNumAliases($page, $replace, $diff=0) {
		foreach ($replace as $rep) {
			foreach ($rep[3] as $a) {
				if (strpos($page, $a) !== false) {
					$page = str_replace($a, $rep[0], $page);
					$diff += ($rep[2] - $rep[1]);
				}
			}
		}
		return array($page, $diff);
	}

	/**
	 * Returns timestamp in seconds from formatted date-time.
	 * @param $date (string) Formatted date-time.
	 * @return int seconds.
	 * @since 5.9.152 (2012-03-23)
	 * @public static
	 */
	public static function getTimestamp($date) {
		if (($date[0] == 'D') AND ($date[1] == ':')) {
			// remove date prefix if present
			$date = substr($date, 2);
		}
		return strtotime($date);
	}

	/**
	 * Returns a formatted date-time.
	 * @param $time (int) Time in seconds.
	 * @return string escaped date string.
	 * @since 5.9.152 (2012-03-23)
	 * @public static
	 */
	public static function getFormattedDate($time) {
		return substr_replace(date('YmdHisO', intval($time)), '\'', (0 - 2), 0).'\'';
	}

	/**
	 * Returns a string containing random data to be used as a seed for encryption methods.
	 * @param $seed (string) starting seed value
	 * @return string containing random data
	 * @author Nicola Asuni
	 * @since 5.9.006 (2010-10-19)
	 * @public static
	 */
	public static function getRandomSeed($seed='') {
		$rnd = uniqid(rand().microtime(true), true);
		if (function_exists('posix_getpid')) {
			$rnd .= posix_getpid();
		}
		if (function_exists('openssl_random_pseudo_bytes') AND (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) {
			
			$rnd .= openssl_random_pseudo_bytes(512);
		} else {
			for ($i = 0; $i < 23; ++$i) {
				$rnd .= uniqid('', true);
			}
		}
		return $rnd.$seed.__FILE__.serialize($_SERVER).microtime(true);
	}

	/**
	 * Encrypts a string using MD5 and returns it's value as a binary string.
	 * @param $str (string) input string
	 * @return String MD5 encrypted binary string
	 * @since 2.0.000 (2008-01-02)
	 * @public static
	 */
	public static function _md5_16($str) {
		return pack('H*', md5($str));
	}

	/**
	 * Returns the input text exrypted using AES algorithm and the specified key.
	 * This method requires openssl or mcrypt. Text is padded to 16bytes blocks
	 * @param $key (string) encryption key
	 * @param $text (String) input text to be encrypted
	 * @return String encrypted text
	 * @author Nicola Asuni
	 * @since 5.0.005 (2010-05-11)
	 * @public static
	 */
	public static function _AES($key, $text) {
		// padding (RFC 2898, PKCS #5: Password-Based Cryptography Specification Version 2.0)
		$padding = 16 - (strlen($text) % 16);
		$text .= str_repeat(chr($padding), $padding);
		if (extension_loaded('openssl')) {
			$iv = openssl_random_pseudo_bytes (openssl_cipher_iv_length('aes-256-cbc'));
			$text = openssl_encrypt($text, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
			return $iv.substr($text, 0, -16);
		}
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
		$text = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $text, MCRYPT_MODE_CBC, $iv);
		$text = $iv.$text;
		return $text;
	}

	/**
	 * Returns the input text exrypted using AES algorithm and the specified key.
	 * This method requires openssl or mcrypt. Text is not padded
	 * @param $key (string) encryption key
	 * @param $text (String) input text to be encrypted
	 * @return String encrypted text
	 * @author Nicola Asuni
	 
	 * @public static
	 */
	public static function _AESnopad($key, $text) {
		if (extension_loaded('openssl')) {
			$iv = str_repeat("\x00", openssl_cipher_iv_length('aes-256-cbc'));
			$text = openssl_encrypt($text, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
			return substr($text, 0, -16);
		}
		$iv = str_repeat("\x00", mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));
		$text = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $text, MCRYPT_MODE_CBC, $iv);
		return $text;
	}

	/**
	 * Returns the input text encrypted using RC4 algorithm and the specified key.
	 * RC4 is the standard encryption algorithm used in PDF format
	 * @param $key (string) Encryption key.
	 * @param $text (String) Input text to be encrypted.
	 * @param $last_enc_key (String) Reference to last RC4 key encrypted.
	 * @param $last_enc_key_c (String) Reference to last RC4 computed key.
	 * @return String encrypted text
	 * @since 2.0.000 (2008-01-02)
	 * @author Klemen Vodopivec, Nicola Asuni
	 * @public static
	 */
	public static function _RC4($key, $text, &$last_enc_key, &$last_enc_key_c) {
		if (function_exists('mcrypt_encrypt') AND ($out = @mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $text, MCRYPT_MODE_STREAM, ''))) {
			
			return $out;
		}
		if ($last_enc_key != $key) {
			$k = str_repeat($key, ((256 / strlen($key)) + 1));
			$rc4 = range(0, 255);
			$j = 0;
			for ($i = 0; $i < 256; ++$i) {
				$t = $rc4[$i];
				$j = ($j + $t + ord($k[$i])) % 256;
				$rc4[$i] = $rc4[$j];
				$rc4[$j] = $t;
			}
			$last_enc_key = $key;
			$last_enc_key_c = $rc4;
		} else {
			$rc4 = $last_enc_key_c;
		}
		$len = strlen($text);
		$a = 0;
		$b = 0;
		$out = '';
		for ($i = 0; $i < $len; ++$i) {
			$a = ($a + 1) % 256;
			$t = $rc4[$a];
			$b = ($b + $t) % 256;
			$rc4[$a] = $rc4[$b];
			$rc4[$b] = $t;
			$k = $rc4[($rc4[$a] + $rc4[$b]) % 256];
			$out .= chr(ord($text[$i]) ^ $k);
		}
		return $out;
	}

	/**
	 * Return the permission code used on encryption (P value).
	 * @param $permissions (Array) the set of permissions (specify the ones you want to block).
	 * @param $mode (int) encryption strength: 0 = RC4 40 bit; 1 = RC4 128 bit; 2 = AES 128 bit; 3 = AES 256 bit.
	 * @since 5.0.005 (2010-05-12)
	 * @author Nicola Asuni
	 * @public static
	 */
	public static function getUserPermissionCode($permissions, $mode=0) {
		$options = array(
			'owner' => 2, // bit 2 -- inverted logic: cleared by default
			'print' => 4, // bit 3
			'modify' => 8, // bit 4
			'copy' => 16, // bit 5
			'annot-forms' => 32, // bit 6
			'fill-forms' => 256, // bit 9
			'extract' => 512, // bit 10
			'assemble' => 1024,// bit 11
			'print-high' => 2048 // bit 12
			);
		$protection = 2147422012; // 32 bit: (01111111 11111111 00001111 00111100)
		foreach ($permissions as $permission) {
			if (isset($options[$permission])) {
				if (($mode > 0) OR ($options[$permission] <= 32)) {
					// set only valid permissions
					if ($options[$permission] == 2) {
						// the logic for bit 2 is inverted (cleared by default)
						$protection += $options[$permission];
					} else {
						$protection -= $options[$permission];
					}
				}
			}
		}
		return $protection;
	}

	/**
	 * Convert hexadecimal string to string
	 * @param $bs (string) byte-string to convert
	 * @return String
	 * @since 5.0.005 (2010-05-12)
	 * @author Nicola Asuni
	 * @public static
	 */
	public static function convertHexStringToString($bs) {
		$string = ''; // string to be returned
		$bslength = strlen($bs);
		if (($bslength % 2) != 0) {
			// padding
			$bs .= '0';
			++$bslength;
		}
		for ($i = 0; $i < $bslength; $i += 2) {
			$string .= chr(hexdec($bs[$i].$bs[($i + 1)]));
		}
		return $string;
	}

	/**
	 * Convert string to hexadecimal string (byte string)
	 * @param $s (string) string to convert
	 * @return byte string
	 * @since 5.0.010 (2010-05-17)
	 * @author Nicola Asuni
	 * @public static
	 */
	public static function convertStringToHexString($s) {
		$bs = '';
		$chars = preg_split('//', $s, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($chars as $c) {
			$bs .= sprintf('%02s', dechex(ord($c)));
		}
		return $bs;
	}

	/**
	 * Convert encryption P value to a string of bytes, low-order byte first.
	 * @param $protection (string) 32bit encryption permission value (P value)
	 * @return String
	 * @since 5.0.005 (2010-05-12)
	 * @author Nicola Asuni
	 * @public static
	 */
	public static function getEncPermissionsString($protection) {
		$binprot = sprintf('%032b', $protection);
		$str = chr(bindec(substr($binprot, 24, 8)));
		$str .= chr(bindec(substr($binprot, 16, 8)));
		$str .= chr(bindec(substr($binprot, 8, 8)));
		$str .= chr(bindec(substr($binprot, 0, 8)));
		return $str;
	}

	/**
	 * Encode a name object.
	 * @param $name (string) Name object to encode.
	 * @return (string) Encoded name object.
	 * @author Nicola Asuni
	 * @since 5.9.097 (2011-06-23)
	 * @public static
	 */
	public static function encodeNameObject($name) {
		$escname = '';
		$length = strlen($name);
		for ($i = 0; $i < $length; ++$i) {
			$chr = $name[$i];
			if (preg_match('/[0-9a-zA-Z#_=-]/', $chr) == 1) {
				$escname .= $chr;
			} else {
				$escname .= sprintf('#%02X', ord($chr));
			}
		}
		return $escname;
	}

	/**
	 * Convert JavaScript form fields properties array to Annotation Properties array.
	 * @param $prop (array) javascript field properties. Possible values are described on official Javascript for Acrobat API reference.
	 * @param $spot_colors (array) Reference to spot colors array.
	 * @param $rtl (boolean) True if in Right-To-Left text direction mode, false otherwise.
	 * @return array of annotation properties
	 * @author Nicola Asuni
	 * @since 4.8.000 (2009-09-06)
	 * @public static
	 */
	public static function getAnnotOptFromJSProp($prop, &$spot_colors, $rtl=false) {
		if (isset($prop['aopt']) AND is_array($prop['aopt'])) {
			// the annotation options area lready defined
			return $prop['aopt'];
		}
		$opt = array(); // value to be returned
		// alignment: Controls how the text is laid out within the text field.
		if (isset($prop['alignment'])) {
			switch ($prop['alignment']) {
				case 'left': {
					$opt['q'] = 0;
					break;
				}
				case 'center': {
					$opt['q'] = 1;
					break;
				}
				case 'right': {
					$opt['q'] = 2;
					break;
				}
				default: {
					$opt['q'] = ($rtl)?2:0;
					break;
				}
			}
		}
		// lineWidth: Specifies the thickness of the border when stroking the perimeter of a field's rectangle.
		if (isset($prop['lineWidth'])) {
			$linewidth = intval($prop['lineWidth']);
		} else {
			$linewidth = 1;
		}
		// borderStyle: The border style for a field.
		if (isset($prop['borderStyle'])) {
			switch ($prop['borderStyle']) {
				case 'border.d':
				case 'dashed': {
					$opt['border'] = array(0, 0, $linewidth, array(3, 2));
					$opt['bs'] = array('w'=>$linewidth, 's'=>'D', 'd'=>array(3, 2));
					break;
				}
				case 'border.b':
				case 'beveled': {
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w'=>$linewidth, 's'=>'B');
					break;
				}
				case 'border.i':
				case 'inset': {
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w'=>$linewidth, 's'=>'I');
					break;
				}
				case 'border.u':
				case 'underline': {
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w'=>$linewidth, 's'=>'U');
					break;
				}
				case 'border.s':
				case 'solid': {
					$opt['border'] = array(0, 0, $linewidth);
					$opt['bs'] = array('w'=>$linewidth, 's'=>'S');
					break;
				}
				default: {
					break;
				}
			}
		}
		if (isset($prop['border']) AND is_array($prop['border'])) {
			$opt['border'] = $prop['border'];
		}
		if (!isset($opt['mk'])) {
			$opt['mk'] = array();
		}
		if (!isset($opt['mk']['if'])) {
			$opt['mk']['if'] = array();
		}
		$opt['mk']['if']['a'] = array(0.5, 0.5);
		// buttonAlignX: Controls how space is distributed from the left of the button face with respect to the icon.
		if (isset($prop['buttonAlignX'])) {
			$opt['mk']['if']['a'][0] = $prop['buttonAlignX'];
		}
		// buttonAlignY: Controls how unused space is distributed from the bottom of the button face with respect to the icon.
		if (isset($prop['buttonAlignY'])) {
			$opt['mk']['if']['a'][1] = $prop['buttonAlignY'];
		}
		// buttonFitBounds: If true, the extent to which the icon may be scaled is set to the bounds of the button field.
		if (isset($prop['buttonFitBounds']) AND ($prop['buttonFitBounds'] == 'true')) {
			$opt['mk']['if']['fb'] = true;
		}
		// buttonScaleHow: Controls how the icon is scaled (if necessary) to fit inside the button face.
		if (isset($prop['buttonScaleHow'])) {
			switch ($prop['buttonScaleHow']) {
				case 'scaleHow.proportional': {
					$opt['mk']['if']['s'] = 'P';
					break;
				}
				case 'scaleHow.anamorphic': {
					$opt['mk']['if']['s'] = 'A';
					break;
				}
			}
		}
		// buttonScaleWhen: Controls when an icon is scaled to fit inside the button face.
		if (isset($prop['buttonScaleWhen'])) {
			switch ($prop['buttonScaleWhen']) {
				case 'scaleWhen.always': {
					$opt['mk']['if']['sw'] = 'A';
					break;
				}
				case 'scaleWhen.never': {
					$opt['mk']['if']['sw'] = 'N';
					break;
				}
				case 'scaleWhen.tooBig': {
					$opt['mk']['if']['sw'] = 'B';
					break;
				}
				case 'scaleWhen.tooSmall': {
					$opt['mk']['if']['sw'] = 'S';
					break;
				}
			}
		}
		// buttonPosition: Controls how the text and the icon of the button are positioned with respect to each other within the button face.
		if (isset($prop['buttonPosition'])) {
			switch ($prop['buttonPosition']) {
				case 0:
				case 'position.textOnly': {
					$opt['mk']['tp'] = 0;
					break;
				}
				case 1:
				case 'position.iconOnly': {
					$opt['mk']['tp'] = 1;
					break;
				}
				case 2:
				case 'position.iconTextV': {
					$opt['mk']['tp'] = 2;
					break;
				}
				case 3:
				case 'position.textIconV': {
					$opt['mk']['tp'] = 3;
					break;
				}
				case 4:
				case 'position.iconTextH': {
					$opt['mk']['tp'] = 4;
					break;
				}
				case 5:
				case 'position.textIconH': {
					$opt['mk']['tp'] = 5;
					break;
				}
				case 6:
				case 'position.overlay': {
					$opt['mk']['tp'] = 6;
					break;
				}
			}
		}
		// fillColor: Specifies the background color for a field.
		if (isset($prop['fillColor'])) {
			if (is_array($prop['fillColor'])) {
				$opt['mk']['bg'] = $prop['fillColor'];
			} else {
				$opt['mk']['bg'] = TCPDF_COLORS::convertHTMLColorToDec($prop['fillColor'], $spot_colors);
			}
		}
		// strokeColor: Specifies the stroke color for a field that is used to stroke the rectangle of the field with a line as large as the line width.
		if (isset($prop['strokeColor'])) {
			if (is_array($prop['strokeColor'])) {
				$opt['mk']['bc'] = $prop['strokeColor'];
			} else {
				$opt['mk']['bc'] = TCPDF_COLORS::convertHTMLColorToDec($prop['strokeColor'], $spot_colors);
			}
		}
		// rotation: The rotation of a widget in counterclockwise increments.
		if (isset($prop['rotation'])) {
			$opt['mk']['r'] = $prop['rotation'];
		}
		// charLimit: Limits the number of characters that a user can type into a text field.
		if (isset($prop['charLimit'])) {
			$opt['maxlen'] = intval($prop['charLimit']);
		}
		if (!isset($ff)) {
			$ff = 0; 
		}
		// readonly: The read-only characteristic of a field. If a field is read-only, the user can see the field but cannot change it.
		if (isset($prop['readonly']) AND ($prop['readonly'] == 'true')) {
			$ff += 1 << 0;
		}
		// required: Specifies whether a field requires a value.
		if (isset($prop['required']) AND ($prop['required'] == 'true')) {
			$ff += 1 << 1;
		}
		// multiline: Controls how text is wrapped within the field.
		if (isset($prop['multiline']) AND ($prop['multiline'] == 'true')) {
			$ff += 1 << 12;
		}
		// password: Specifies whether the field should display asterisks when data is entered in the field.
		if (isset($prop['password']) AND ($prop['password'] == 'true')) {
			$ff += 1 << 13;
		}
		// NoToggleToOff: If set, exactly one radio button shall be selected at all times; selecting the currently selected button has no effect.
		if (isset($prop['NoToggleToOff']) AND ($prop['NoToggleToOff'] == 'true')) {
			$ff += 1 << 14;
		}
		// Radio: If set, the field is a set of radio buttons.
		if (isset($prop['Radio']) AND ($prop['Radio'] == 'true')) {
			$ff += 1 << 15;
		}
		// Pushbutton: If set, the field is a pushbutton that does not retain a permanent value.
		if (isset($prop['Pushbutton']) AND ($prop['Pushbutton'] == 'true')) {
			$ff += 1 << 16;
		}
		// Combo: If set, the field is a combo box; if clear, the field is a list box.
		if (isset($prop['Combo']) AND ($prop['Combo'] == 'true')) {
			$ff += 1 << 17;
		}
		// editable: Controls whether a combo box is editable.
		if (isset($prop['editable']) AND ($prop['editable'] == 'true')) {
			$ff += 1 << 18;
		}
		// Sort: If set, the field's option items shall be sorted alphabetically.
		if (isset($prop['Sort']) AND ($prop['Sort'] == 'true')) {
			$ff += 1 << 19;
		}
		// fileSelect: If true, sets the file-select flag in the Options tab of the text field (Field is Used for File Selection).
		if (isset($prop['fileSelect']) AND ($prop['fileSelect'] == 'true')) {
			$ff += 1 << 20;
		}
		// multipleSelection: If true, indicates that a list box allows a multiple selection of items.
		if (isset($prop['multipleSelection']) AND ($prop['multipleSelection'] == 'true')) {
			$ff += 1 << 21;
		}
		// doNotSpellCheck: If true, spell checking is not performed on this editable text field.
		if (isset($prop['doNotSpellCheck']) AND ($prop['doNotSpellCheck'] == 'true')) {
			$ff += 1 << 22;
		}
		// doNotScroll: If true, the text field does not scroll and the user, therefore, is limited by the rectangular region designed for the field.
		if (isset($prop['doNotScroll']) AND ($prop['doNotScroll'] == 'true')) {
			$ff += 1 << 23;
		}
		// comb: If set to true, the field background is drawn as series of boxes (one for each character in the value of the field) and each character of the content is drawn within those boxes. The number of boxes drawn is determined from the charLimit property. It applies only to text fields. The setter will also raise if any of the following field properties are also set multiline, password, and fileSelect. A side-effect of setting this property is that the doNotScroll property is also set.
		if (isset($prop['comb']) AND ($prop['comb'] == 'true')) {
			$ff += 1 << 24;
		}
		// radiosInUnison: If false, even if a group of radio buttons have the same name and export value, they behave in a mutually exclusive fashion, like HTML radio buttons.
		if (isset($prop['radiosInUnison']) AND ($prop['radiosInUnison'] == 'true')) {
			$ff += 1 << 25;
		}
		// richText: If true, the field allows rich text formatting.
		if (isset($prop['richText']) AND ($prop['richText'] == 'true')) {
			$ff += 1 << 25;
		}
		// commitOnSelChange: Controls whether a field value is committed after a selection change.
		if (isset($prop['commitOnSelChange']) AND ($prop['commitOnSelChange'] == 'true')) {
			$ff += 1 << 26;
		}
		$opt['ff'] = $ff;
		// defaultValue: The default value of a field - that is, the value that the field is set to when the form is reset.
		if (isset($prop['defaultValue'])) {
			$opt['dv'] = $prop['defaultValue'];
		}
		$f = 4; 
		// readonly: The read-only characteristic of a field. If a field is read-only, the user can see the field but cannot change it.
		if (isset($prop['readonly']) AND ($prop['readonly'] == 'true')) {
			$f += 1 << 6;
		}
		// display: Controls whether the field is hidden or visible on screen and in print.
		if (isset($prop['display'])) {
			if ($prop['display'] == 'display.visible') {
				
			} elseif ($prop['display'] == 'display.hidden') {
				$f += 1 << 1;
			} elseif ($prop['display'] == 'display.noPrint') {
				$f -= 1 << 2;
			} elseif ($prop['display'] == 'display.noView') {
				$f += 1 << 5;
			}
		}
		$opt['f'] = $f;
		// currentValueIndices: Reads and writes single or multiple values of a list box or combo box.
		if (isset($prop['currentValueIndices']) AND is_array($prop['currentValueIndices'])) {
			$opt['i'] = $prop['currentValueIndices'];
		}
		// value: The value of the field data that the user has entered.
		if (isset($prop['value'])) {
			if (is_array($prop['value'])) {
				$opt['opt'] = array();
				foreach ($prop['value'] AS $key => $optval) {
					// exportValues: An array of strings representing the export values for the field.
					if (isset($prop['exportValues'][$key])) {
						$opt['opt'][$key] = array($prop['exportValues'][$key], $prop['value'][$key]);
					} else {
						$opt['opt'][$key] = $prop['value'][$key];
					}
				}
			} else {
				$opt['v'] = $prop['value'];
			}
		}
		// richValue: This property specifies the text contents and formatting of a rich text field.
		if (isset($prop['richValue'])) {
			$opt['rv'] = $prop['richValue'];
		}
		// submitName: If nonempty, used during form submission instead of name. Only applicable if submitting in HTML format (that is, URL-encoded).
		if (isset($prop['submitName'])) {
			$opt['tm'] = $prop['submitName'];
		}
		// name: Fully qualified field name.
		if (isset($prop['name'])) {
			$opt['t'] = $prop['name'];
		}
		// userName: The user name (short description string) of the field.
		if (isset($prop['userName'])) {
			$opt['tu'] = $prop['userName'];
		}
		// highlight: Defines how a button reacts when a user clicks it.
		if (isset($prop['highlight'])) {
			switch ($prop['highlight']) {
				case 'none':
				case 'highlight.n': {
					$opt['h'] = 'N';
					break;
				}
				case 'invert':
				case 'highlight.i': {
					$opt['h'] = 'i';
					break;
				}
				case 'push':
				case 'highlight.p': {
					$opt['h'] = 'P';
					break;
				}
				case 'outline':
				case 'highlight.o': {
					$opt['h'] = 'O';
					break;
				}
			}
		}
		// Unsupported options:
		
		
		
		
		
		return $opt;
	}

	/**
	 * Format the page numbers.
	 * This method can be overriden for custom formats.
	 * @param $num (int) page number
	 * @since 4.2.005 (2008-11-06)
	 * @public static
	 */
	public static function formatPageNumber($num) {
		return number_format((float)$num, 0, '', '.');
	}

	/**
	 * Format the page numbers on the Table Of Content.
	 * This method can be overriden for custom formats.
	 * @param $num (int) page number
	 * @since 4.5.001 (2009-01-04)
	 * @see addTOC(), addHTMLTOC()
	 * @public static
	 */
	public static function formatTOCPageNumber($num) {
		return number_format((float)$num, 0, '', '.');
	}

	/**
	 * Extracts the CSS properties from a CSS string.
	 * @param $cssdata (string) string containing CSS definitions.
	 * @return An array where the keys are the CSS selectors and the values are the CSS properties.
	 * @author Nicola Asuni
	 * @since 5.1.000 (2010-05-25)
	 * @public static
	 */
	public static function extractCSSproperties($cssdata) {
		if (empty($cssdata)) {
			return array();
		}
		// remove comments
		$cssdata = preg_replace('/\/\*[^\*]*\*\//', '', $cssdata);
		// remove newlines and multiple spaces
		$cssdata = preg_replace('/[\s]+/', ' ', $cssdata);
		// remove some spaces
		$cssdata = preg_replace('/[\s]*([;:\{\}]{1})[\s]*/', '\\1', $cssdata);
		// remove empty blocks
		$cssdata = preg_replace('/([^\}\{]+)\{\}/', '', $cssdata);
		// replace media type parenthesis
		$cssdata = preg_replace('/@media[\s]+([^\{]*)\{/i', '@media \\1§', $cssdata);
		$cssdata = preg_replace('/\}\}/si', '}§', $cssdata);
		// trim string
		$cssdata = trim($cssdata);
		// find media blocks (all, braille, embossed, handheld, print, projection, screen, speech, tty, tv)
		$cssblocks = array();
		$matches = array();
		if (preg_match_all('/@media[\s]+([^\§]*)§([^§]*)§/i', $cssdata, $matches) > 0) {
			foreach ($matches[1] as $key => $type) {
				$cssblocks[$type] = $matches[2][$key];
			}
			// remove media blocks
			$cssdata = preg_replace('/@media[\s]+([^\§]*)§([^§]*)§/i', '', $cssdata);
		}
		// keep 'all' and 'print' media, other media types are discarded
		if (isset($cssblocks['all']) AND !empty($cssblocks['all'])) {
			$cssdata .= $cssblocks['all'];
		}
		if (isset($cssblocks['print']) AND !empty($cssblocks['print'])) {
			$cssdata .= $cssblocks['print'];
		}
		// reset css blocks array
		$cssblocks = array();
		$matches = array();
		// explode css data string into array
		if (substr($cssdata, -1) == '}') {
			// remove last parethesis
			$cssdata = substr($cssdata, 0, -1);
		}
		$matches = explode('}', $cssdata);
		foreach ($matches as $key => $block) {
			// index 0 contains the CSS selector, index 1 contains CSS properties
			$cssblocks[$key] = explode('{', $block);
			if (!isset($cssblocks[$key][1])) {
				// remove empty definitions
				unset($cssblocks[$key]);
			}
		}
		// split groups of selectors (comma-separated list of selectors)
		foreach ($cssblocks as $key => $block) {
			if (strpos($block[0], ',') > 0) {
				$selectors = explode(',', $block[0]);
				foreach ($selectors as $sel) {
					$cssblocks[] = array(0 => trim($sel), 1 => $block[1]);
				}
				unset($cssblocks[$key]);
			}
		}
		// covert array to selector => properties
		$cssdata = array();
		foreach ($cssblocks as $block) {
			$selector = $block[0];
			// calculate selector's specificity
			$matches = array();
			$a = 0; // the declaration is not from is a 'style' attribute
			$b = intval(preg_match_all('/[\#]/', $selector, $matches)); // number of ID attributes
			$c = intval(preg_match_all('/[\[\.]/', $selector, $matches)); // number of other attributes
			$c += intval(preg_match_all('/[\:]link|visited|hover|active|focus|target|lang|enabled|disabled|checked|indeterminate|root|nth|first|last|only|empty|contains|not/i', $selector, $matches)); // number of pseudo-classes
			$d = intval(preg_match_all('/[\>\+\~\s]{1}[a-zA-Z0-9]+/', ' '.$selector, $matches)); // number of element names
			$d += intval(preg_match_all('/[\:][\:]/', $selector, $matches)); // number of pseudo-elements
			$specificity = $a.$b.$c.$d;
			// add specificity to the beginning of the selector
			$cssdata[$specificity.' '.$selector] = $block[1];
		}
		// sort selectors alphabetically to account for specificity
		ksort($cssdata, SORT_STRING);
		
		return $cssdata;
	}

	/**
	 * Cleanup HTML code (requires HTML Tidy library).
	 * @param $html (string) htmlcode to fix
	 * @param $default_css (string) CSS commands to add
	 * @param $tagvs (array) parameters for setHtmlVSpace method
	 * @param $tidy_options (array) options for tidy_parse_string function
	 * @param $tagvspaces (array) Array of vertical spaces for tags.
	 * @return string XHTML code cleaned up
	 * @author Nicola Asuni
	 * @since 5.9.017 (2010-11-16)
	 * @see setHtmlVSpace()
	 * @public static
	 */
	public static function fixHTMLCode($html, $default_css='', $tagvs='', $tidy_options='', &$tagvspaces) {
		// configure parameters for HTML Tidy
		if ($tidy_options === '') {
			$tidy_options = array (
				'clean' => 1,
				'drop-empty-paras' => 0,
				'drop-proprietary-attributes' => 1,
				'fix-backslash' => 1,
				'hide-comments' => 1,
				'join-styles' => 1,
				'lower-literals' => 1,
				'merge-divs' => 1,
				'merge-spans' => 1,
				'output-xhtml' => 1,
				'word-2000' => 1,
				'wrap' => 0,
				'output-bom' => 0,
				//'char-encoding' => 'utf8',
				//'input-encoding' => 'utf8',
				//'output-encoding' => 'utf8'
			);
		}
		// clean up the HTML code
		$tidy = tidy_parse_string($html, $tidy_options);
		// fix the HTML
		$tidy->cleanRepair();
		// get the CSS part
		$tidy_head = tidy_get_head($tidy);
		$css = $tidy_head->value;
		$css = preg_replace('/<style([^>]+)>/ims', '<style>', $css);
		$css = preg_replace('/<\/style>(.*)<style>/ims', "\n", $css);
		$css = str_replace('/*<![CDATA[*/', '', $css);
		$css = str_replace('/*]]>*/', '', $css);
		preg_match('/<style>(.*)<\/style>/ims', $css, $matches);
		if (isset($matches[1])) {
			$css = strtolower($matches[1]);
		} else {
			$css = '';
		}
		
		$css = '<style>'.$default_css.$css.'</style>';
		// get the body part
		$tidy_body = tidy_get_body($tidy);
		$html = $tidy_body->value;
		// fix some self-closing tags
		$html = str_replace('<br>', '<br />', $html);
		// remove some empty tag blocks
		$html = preg_replace('/<div([^\>]*)><\/div>/', '', $html);
		$html = preg_replace('/<p([^\>]*)><\/p>/', '', $html);
		if ($tagvs !== '') {
			// set vertical space for some XHTML tags
			$tagvspaces = $tagvs;
		}
		
		return $css.$html;
	}

	/**
	 * Returns true if the CSS selector is valid for the selected HTML tag
	 * @param $dom (array) array of HTML tags and properties
	 * @param $key (int) key of the current HTML tag
	 * @param $selector (string) CSS selector string
	 * @return true if the selector is valid, false otherwise
	 * @since 5.1.000 (2010-05-25)
	 * @public static
	 */
	public static function isValidCSSSelectorForTag($dom, $key, $selector) {
		$valid = false; // value to be returned
		$tag = $dom[$key]['value'];
		$class = array();
		if (isset($dom[$key]['attribute']['class']) AND !empty($dom[$key]['attribute']['class'])) {
			$class = explode(' ', strtolower($dom[$key]['attribute']['class']));
		}
		$id = '';
		if (isset($dom[$key]['attribute']['id']) AND !empty($dom[$key]['attribute']['id'])) {
			$id = strtolower($dom[$key]['attribute']['id']);
		}
		$selector = preg_replace('/([\>\+\~\s]{1})([\.]{1})([^\>\+\~\s]*)/si', '\\1*.\\3', $selector);
		$matches = array();
		if (preg_match_all('/([\>\+\~\s]{1})([a-zA-Z0-9\*]+)([^\>\+\~\s]*)/si', $selector, $matches, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE) > 0) {
			$parentop = array_pop($matches[1]);
			$operator = $parentop[0];
			$offset = $parentop[1];
			$lasttag = array_pop($matches[2]);
			$lasttag = strtolower(trim($lasttag[0]));
			if (($lasttag == '*') OR ($lasttag == $tag)) {
				// the last element on selector is our tag or 'any tag'
				$attrib = array_pop($matches[3]);
				$attrib = strtolower(trim($attrib[0]));
				if (!empty($attrib)) {
					// check if matches class, id, attribute, pseudo-class or pseudo-element
					switch ($attrib[0]) {
						case '.': { 
							if (in_array(substr($attrib, 1), $class)) {
								$valid = true;
							}
							break;
						}
						case '#': { // ID
							if (substr($attrib, 1) == $id) {
								$valid = true;
							}
							break;
						}
						case '[': { // attribute
							$attrmatch = array();
							if (preg_match('/\[([a-zA-Z0-9]*)[\s]*([\~\^\$\*\|\=]*)[\s]*["]?([^"\]]*)["]?\]/i', $attrib, $attrmatch) > 0) {
								$att = strtolower($attrmatch[1]);
								$val = $attrmatch[3];
								if (isset($dom[$key]['attribute'][$att])) {
									switch ($attrmatch[2]) {
										case '=': {
											if ($dom[$key]['attribute'][$att] == $val) {
												$valid = true;
											}
											break;
										}
										case '~=': {
											if (in_array($val, explode(' ', $dom[$key]['attribute'][$att]))) {
												$valid = true;
											}
											break;
										}
										case '^=': {
											if ($val == substr($dom[$key]['attribute'][$att], 0, strlen($val))) {
												$valid = true;
											}
											break;
										}
										case '$=': {
											if ($val == substr($dom[$key]['attribute'][$att], -strlen($val))) {
												$valid = true;
											}
											break;
										}
										case '*=': {
											if (strpos($dom[$key]['attribute'][$att], $val) !== false) {
												$valid = true;
											}
											break;
										}
										case '|=': {
											if ($dom[$key]['attribute'][$att] == $val) {
												$valid = true;
											} elseif (preg_match('/'.$val.'[\-]{1}/i', $dom[$key]['attribute'][$att]) > 0) {
												$valid = true;
											}
											break;
										}
										default: {
											$valid = true;
										}
									}
								}
							}
							break;
						}
						case ':': { // pseudo-class or pseudo-element
							if ($attrib[1] == ':') { // pseudo-element
								// pseudo-elements are not supported!
								
							} else { // pseudo-class
								// pseudo-classes are not supported!
								
							}
							break;
						}
					} // end of switch
				} else {
					$valid = true;
				}
				if ($valid AND ($offset > 0)) {
					$valid = false;
					// check remaining selector part
					$selector = substr($selector, 0, $offset);
					switch ($operator) {
						case ' ': { // descendant of an element
							while ($dom[$key]['parent'] > 0) {
								if (self::isValidCSSSelectorForTag($dom, $dom[$key]['parent'], $selector)) {
									$valid = true;
									break;
								} else {
									$key = $dom[$key]['parent'];
								}
							}
							break;
						}
						case '>': { // child of an element
							$valid = self::isValidCSSSelectorForTag($dom, $dom[$key]['parent'], $selector);
							break;
						}
						case '+': { // immediately preceded by an element
							for ($i = ($key - 1); $i > $dom[$key]['parent']; --$i) {
								if ($dom[$i]['tag'] AND $dom[$i]['opening']) {
									$valid = self::isValidCSSSelectorForTag($dom, $i, $selector);
									break;
								}
							}
							break;
						}
						case '~': { // preceded by an element
							for ($i = ($key - 1); $i > $dom[$key]['parent']; --$i) {
								if ($dom[$i]['tag'] AND $dom[$i]['opening']) {
									if (self::isValidCSSSelectorForTag($dom, $i, $selector)) {
										break;
									}
								}
							}
							break;
						}
					}
				}
			}
		}
		return $valid;
	}

	/**
	 * Returns the styles array that apply for the selected HTML tag.
	 * @param $dom (array) array of HTML tags and properties
	 * @param $key (int) key of the current HTML tag
	 * @param $css (array) array of CSS properties
	 * @return array containing CSS properties
	 * @since 5.1.000 (2010-05-25)
	 * @public static
	 */
	public static function getCSSdataArray($dom, $key, $css) {
		$cssarray = array(); // style to be returned
		// get parent CSS selectors
		$selectors = array();
		if (isset($dom[($dom[$key]['parent'])]['csssel'])) {
			$selectors = $dom[($dom[$key]['parent'])]['csssel'];
		}
		// get all styles that apply
		foreach($css as $selector => $style) {
			$pos = strpos($selector, ' ');
			// get specificity
			$specificity = substr($selector, 0, $pos);
			// remove specificity
			$selector = substr($selector, $pos);
			// check if this selector apply to current tag
			if (self::isValidCSSSelectorForTag($dom, $key, $selector)) {
				if (!in_array($selector, $selectors)) {
					// add style if not already added on parent selector
					$cssarray[] = array('k' => $selector, 's' => $specificity, 'c' => $style);
					$selectors[] = $selector;
				}
			}
		}
		if (isset($dom[$key]['attribute']['style'])) {
			// attach inline style (latest properties have high priority)
			$cssarray[] = array('k' => '', 's' => '1000', 'c' => $dom[$key]['attribute']['style']);
		}
		// order the css array to account for specificity
		$cssordered = array();
		foreach ($cssarray as $key => $val) {
			$skey = sprintf('%04d', $key);
			$cssordered[$val['s'].'_'.$skey] = $val;
		}
		// sort selectors alphabetically to account for specificity
		ksort($cssordered, SORT_STRING);
		return array($selectors, $cssordered);
	}

	/**
	 * Compact CSS data array into single string.
	 * @param $css (array) array of CSS properties
	 * @return string containing merged CSS properties
	 * @since 5.9.070 (2011-04-19)
	 * @public static
	 */
	public static function getTagStyleFromCSSarray($css) {
		$tagstyle = ''; // value to be returned
		foreach ($css as $style) {
			// split single css commands
			$csscmds = explode(';', $style['c']);
			foreach ($csscmds as $cmd) {
				if (!empty($cmd)) {
					$pos = strpos($cmd, ':');
					if ($pos !== false) {
						$cmd = substr($cmd, 0, ($pos + 1));
						if (strpos($tagstyle, $cmd) !== false) {
							// remove duplicate commands (last commands have high priority)
							$tagstyle = preg_replace('/'.$cmd.'[^;]+/i', '', $tagstyle);
						}
					}
				}
			}
			$tagstyle .= ';'.$style['c'];
		}
		// remove multiple semicolons
		$tagstyle = preg_replace('/[;]+/', ';', $tagstyle);
		return $tagstyle;
	}

	/**
	 * Returns the Roman representation of an integer number
	 * @param $number (int) number to convert
	 * @return string roman representation of the specified number
	 * @since 4.4.004 (2008-12-10)
	 * @public static
	 */
	public static function intToRoman($number) {
		$roman = '';
		while ($number >= 1000) {
			$roman .= 'M';
			$number -= 1000;
		}
		while ($number >= 900) {
			$roman .= 'CM';
			$number -= 900;
		}
		while ($number >= 500) {
			$roman .= 'D';
			$number -= 500;
		}
		while ($number >= 400) {
			$roman .= 'CD';
			$number -= 400;
		}
		while ($number >= 100) {
			$roman .= 'C';
			$number -= 100;
		}
		while ($number >= 90) {
			$roman .= 'XC';
			$number -= 90;
		}
		while ($number >= 50) {
			$roman .= 'L';
			$number -= 50;
		}
		while ($number >= 40) {
			$roman .= 'XL';
			$number -= 40;
		}
		while ($number >= 10) {
			$roman .= 'X';
			$number -= 10;
		}
		while ($number >= 9) {
			$roman .= 'IX';
			$number -= 9;
		}
		while ($number >= 5) {
			$roman .= 'V';
			$number -= 5;
		}
		while ($number >= 4) {
			$roman .= 'IV';
			$number -= 4;
		}
		while ($number >= 1) {
			$roman .= 'I';
			--$number;
		}
		return $roman;
	}

	/**
	 * Find position of last occurrence of a substring in a string
	 * @param $haystack (string) The string to search in.
	 * @param $needle (string) substring to search.
	 * @param $offset (int) May be specified to begin searching an arbitrary number of characters into the string.
	 * @return Returns the position where the needle exists. Returns FALSE if the needle was not found.
	 * @since 4.8.038 (2010-03-13)
	 * @public static
	 */
	public static function revstrpos($haystack, $needle, $offset = 0) {
		$length = strlen($haystack);
		$offset = ($offset > 0)?($length - $offset):abs($offset);
		$pos = strpos(strrev($haystack), strrev($needle), $offset);
		return ($pos === false)?false:($length - $pos - strlen($needle));
	}

	/**
	 * Returns an array of hyphenation patterns.
	 * @param $file (string) TEX file containing hypenation patterns. TEX pattrns can be downloaded from http://www.ctan.org/tex-archive/language/hyph-utf8/tex/generic/hyph-utf8/patterns/
	 * @return array of hyphenation patterns
	 * @author Nicola Asuni
	 * @since 4.9.012 (2010-04-12)
	 * @public static
	 */
	public static function getHyphenPatternsFromTEX($file) {
		// TEX patterns are available at:
		// http://www.ctan.org/tex-archive/language/hyph-utf8/tex/generic/hyph-utf8/patterns/
		$data = file_get_contents($file);
		$patterns = array();
		// remove comments
		$data = preg_replace('/\%[^\n]*/', '', $data);
		// extract the patterns part
		preg_match('/\\\\patterns\{([^\}]*)\}/i', $data, $matches);
		$data = trim(substr($matches[0], 10, -1));
		// extract each pattern
		$patterns_array = preg_split('/[\s]+/', $data);
		// create new language array of patterns
		$patterns = array();
		foreach($patterns_array as $val) {
			if (!TCPDF_STATIC::empty_string($val)) {
				$val = trim($val);
				$val = str_replace('\'', '\\\'', $val);
				$key = preg_replace('/[0-9]+/', '', $val);
				$patterns[$key] = $val;
			}
		}
		return $patterns;
	}

	/**
	 * Get the Path-Painting Operators.
	 * @param $style (string) Style of rendering. Possible values are:
	 * <ul>
	 *   <li>S or D: Stroke the path.</li>
	 *   <li>s or d: Close and stroke the path.</li>
	 *   <li>f or F: Fill the path, using the nonzero winding number rule to determine the region to fill.</li>
	 *   <li>f* or F*: Fill the path, using the even-odd rule to determine the region to fill.</li>
	 *   <li>B or FD or DF: Fill and then stroke the path, using the nonzero winding number rule to determine the region to fill.</li>
	 *   <li>B* or F*D or DF*: Fill and then stroke the path, using the even-odd rule to determine the region to fill.</li>
	 *   <li>b or fd or df: Close, fill, and then stroke the path, using the nonzero winding number rule to determine the region to fill.</li>
	 *   <li>b or f*d or df*: Close, fill, and then stroke the path, using the even-odd rule to determine the region to fill.</li>
	 *   <li>CNZ: Clipping mode using the even-odd rule to determine which regions lie inside the clipping path.</li>
	 *   <li>CEO: Clipping mode using the nonzero winding number rule to determine which regions lie inside the clipping path</li>
	 *   <li>n: End the path object without filling or stroking it.</li>
	 * </ul>
	 * @param $default (string) default style
	 * @author Nicola Asuni
	 * @since 5.0.000 (2010-04-30)
	 * @public static
	 */
	public static function getPathPaintOperator($style, $default='S') {
		$op = '';
		switch($style) {
			case 'S':
			case 'D': {
				$op = 'S';
				break;
			}
			case 's':
			case 'd': {
				$op = 's';
				break;
			}
			case 'f':
			case 'F': {
				$op = 'f';
				break;
			}
			case 'f*':
			case 'F*': {
				$op = 'f*';
				break;
			}
			case 'B':
			case 'FD':
			case 'DF': {
				$op = 'B';
				break;
			}
			case 'B*':
			case 'F*D':
			case 'DF*': {
				$op = 'B*';
				break;
			}
			case 'b':
			case 'fd':
			case 'df': {
				$op = 'b';
				break;
			}
			case 'b*':
			case 'f*d':
			case 'df*': {
				$op = 'b*';
				break;
			}
			case 'CNZ': {
				$op = 'W n';
				break;
			}
			case 'CEO': {
				$op = 'W* n';
				break;
			}
			case 'n': {
				$op = 'n';
				break;
			}
			default: {
				if (!empty($default)) {
					$op = self::getPathPaintOperator($default, '');
				} else {
					$op = '';
				}
			}
		}
		return $op;
	}

	/**
	 * Get the product of two SVG tranformation matrices
	 * @param $ta (array) first SVG tranformation matrix
	 * @param $tb (array) second SVG tranformation matrix
	 * @return transformation array
	 * @author Nicola Asuni
	 * @since 5.0.000 (2010-05-02)
	 * @public static
	 */
	public static function getTransformationMatrixProduct($ta, $tb) {
		$tm = array();
		$tm[0] = ($ta[0] * $tb[0]) + ($ta[2] * $tb[1]);
		$tm[1] = ($ta[1] * $tb[0]) + ($ta[3] * $tb[1]);
		$tm[2] = ($ta[0] * $tb[2]) + ($ta[2] * $tb[3]);
		$tm[3] = ($ta[1] * $tb[2]) + ($ta[3] * $tb[3]);
		$tm[4] = ($ta[0] * $tb[4]) + ($ta[2] * $tb[5]) + $ta[4];
		$tm[5] = ($ta[1] * $tb[4]) + ($ta[3] * $tb[5]) + $ta[5];
		return $tm;
	}

	/**
	 * Get the tranformation matrix from SVG transform attribute
	 * @param $attribute (string) transformation
	 * @return array of transformations
	 * @author Nicola Asuni
	 * @since 5.0.000 (2010-05-02)
	 * @public static
	 */
	public static function getSVGTransformMatrix($attribute) {
		// identity matrix
		$tm = array(1, 0, 0, 1, 0, 0);
		$transform = array();
		if (preg_match_all('/(matrix|translate|scale|rotate|skewX|skewY)[\s]*\(([^\)]+)\)/si', $attribute, $transform, PREG_SET_ORDER) > 0) {
			foreach ($transform as $key => $data) {
				if (!empty($data[2])) {
					$a = 1;
					$b = 0;
					$c = 0;
					$d = 1;
					$e = 0;
					$f = 0;
					$regs = array();
					switch ($data[1]) {
						case 'matrix': {
							if (preg_match('/([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$a = $regs[1];
								$b = $regs[2];
								$c = $regs[3];
								$d = $regs[4];
								$e = $regs[5];
								$f = $regs[6];
							}
							break;
						}
						case 'translate': {
							if (preg_match('/([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$e = $regs[1];
								$f = $regs[2];
							} elseif (preg_match('/([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$e = $regs[1];
							}
							break;
						}
						case 'scale': {
							if (preg_match('/([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$a = $regs[1];
								$d = $regs[2];
							} elseif (preg_match('/([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$a = $regs[1];
								$d = $a;
							}
							break;
						}
						case 'rotate': {
							if (preg_match('/([0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)[\,\s]+([a-z0-9\-\.]+)/si', $data[2], $regs)) {
								$ang = deg2rad($regs[1]);
								$x = $regs[2];
								$y = $regs[3];
								$a = cos($ang);
								$b = sin($ang);
								$c = -$b;
								$d = $a;
								$e = ($x * (1 - $a)) - ($y * $c);
								$f = ($y * (1 - $d)) - ($x * $b);
							} elseif (preg_match('/([0-9\-\.]+)/si', $data[2], $regs)) {
								$ang = deg2rad($regs[1]);
								$a = cos($ang);
								$b = sin($ang);
								$c = -$b;
								$d = $a;
								$e = 0;
								$f = 0;
							}
							break;
						}
						case 'skewX': {
							if (preg_match('/([0-9\-\.]+)/si', $data[2], $regs)) {
								$c = tan(deg2rad($regs[1]));
							}
							break;
						}
						case 'skewY': {
							if (preg_match('/([0-9\-\.]+)/si', $data[2], $regs)) {
								$b = tan(deg2rad($regs[1]));
							}
							break;
						}
					}
					$tm = self::getTransformationMatrixProduct($tm, array($a, $b, $c, $d, $e, $f));
				}
			}
		}
		return $tm;
	}

	/**
	 * Returns the angle in radiants between two vectors
	 * @param $x1 (int) X coordinate of first vector point
	 * @param $y1 (int) Y coordinate of first vector point
	 * @param $x2 (int) X coordinate of second vector point
	 * @param $y2 (int) Y coordinate of second vector point
	 * @author Nicola Asuni
	 * @since 5.0.000 (2010-05-04)
	 * @public static
	 */
	public static function getVectorsAngle($x1, $y1, $x2, $y2) {
		$dprod = ($x1 * $x2) + ($y1 * $y2);
		$dist1 = sqrt(($x1 * $x1) + ($y1 * $y1));
		$dist2 = sqrt(($x2 * $x2) + ($y2 * $y2));
		$angle = acos($dprod / ($dist1 * $dist2));
		if (is_nan($angle)) {
			$angle = M_PI;
		}
		if ((($x1 * $y2) - ($x2 * $y1)) < 0) {
			$angle *= -1;
		}
		return $angle;
	}

	/**
	 * Split string by a regular expression.
	 * This is a wrapper for the preg_split function to avoid the bug: https://bugs.php.net/bug.php?id=45850
	 * @param $pattern (string) The regular expression pattern to search for without the modifiers, as a string.
	 * @param $modifiers (string) The modifiers part of the pattern,
	 * @param $subject (string) The input string.
	 * @param $limit (int) If specified, then only substrings up to limit are returned with the rest of the string being placed in the last substring. A limit of -1, 0 or NULL means "no limit" and, as is standard across PHP, you can use NULL to skip to the flags parameter.
	 * @param $flags (int) The flags as specified on the preg_split PHP function.
	 * @return Returns an array containing substrings of subject split along boundaries matched by pattern.modifier
	 * @author Nicola Asuni
	 * @since 6.0.023
	 * @public static
	 */
	public static function pregSplit($pattern, $modifiers, $subject, $limit=NULL, $flags=NULL) {
		// the bug only happens on PHP 5.2 when using the u modifier
		if ((strpos($modifiers, 'u') === FALSE) OR (count(preg_split('//u', "\n\t", -1, PREG_SPLIT_NO_EMPTY)) == 2)) {
			return preg_split($pattern.$modifiers, $subject, $limit, $flags);
		}
		// preg_split is bugged - try alternative solution
		$ret = array();
		while (($nl = strpos($subject, "\n")) !== FALSE) {
			$ret = array_merge($ret, preg_split($pattern.$modifiers, substr($subject, 0, $nl), $limit, $flags));
			$ret[] = "\n";
			$subject = substr($subject, ($nl + 1));
		}
		if (strlen($subject) > 0) {
			$ret = array_merge($ret, preg_split($pattern.$modifiers, $subject, $limit, $flags));
		}
		return $ret;
	}

    /**
     * Wrapper to use fopen only with local files
     * @param  string $filename The full path to the file to open
     * @param  string $mode     Acceses type for the file ('r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+' or 'e')
     * @return resource         Returns a file pointer resource on success, or FALSE on error.
     * @public static
     */
    public static function fopenLocal($filename, $mode)
    {
        if (strpos($filename, '//') === 0)
        {
            // Share folder on a (windows) server
            // e.g.: "//[MyServerName]/[MySharedFolder]/"
            
            // nothing to change
        }
        elseif (strpos($filename, '://') === false)
        {
            $filename = 'file://'.$filename;
        }
        elseif (stream_is_local($filename) !== true)
        {
            return false;
        }

        return fopen($filename, $mode);
    }

	/**
	 * Check if the URL exist.
	 * @param url (string) URL to check.
	 * @return Returns TRUE if the URL exists; FALSE otherwise.
	 * @public static
	 */
	public static function url_exists($url) {
		$crs = curl_init();
		curl_setopt($crs, CURLOPT_URL, $url);
		curl_setopt($crs, CURLOPT_NOBODY, true);
		curl_setopt($crs, CURLOPT_FAILONERROR, true);
		if ((ini_get('open_basedir') == '') && (!ini_get('safe_mode'))) {
			curl_setopt($crs, CURLOPT_FOLLOWLOCATION, true);
		}
		curl_setopt($crs, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($crs, CURLOPT_TIMEOUT, 30);
		curl_setopt($crs, CURLOPT_SSL_VERIFYPEER, TRUE);
		curl_setopt($crs, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($crs, CURLOPT_USERAGENT, 'tc-lib-file');
		curl_exec($crs);
		$code = curl_getinfo($crs, CURLINFO_HTTP_CODE);
		curl_close($crs);
		return ($code == 200);
	}

	/**
	 * Wrapper for file_exists.
	 * Checks whether a file or directory exists.
	 * Only allows some protocols and local files.
	 * @param filename (string) Path to the file or directory. 
	 * @return Returns TRUE if the file or directory specified by filename exists; FALSE otherwise.  
	 * @public static
	 */
	public static function file_exists($filename) {
		if (preg_match('|^https?://|', $filename) == 1) {
			return self::url_exists($filename);
		}
		if (strpos($filename, '://')) {
			return false; // only support http and https wrappers for security reasons
		}
		return @file_exists($filename);
	}

	/**
	 * Reads entire file into a string.
	 * The file can be also an URL.
	 * @param $file (string) Name of the file or URL to read.
	 * @return The function returns the read data or FALSE on failure. 
	 * @author Nicola Asuni
	 * @since 6.0.025
	 * @public static
	 */
	public static function fileGetContents($file) {
		$alt = array($file);
		
		if ((strlen($file) > 1)
		    && ($file[0] === '/')
		    && ($file[1] !== '/')
		    && !empty($_SERVER['DOCUMENT_ROOT'])
		    && ($_SERVER['DOCUMENT_ROOT'] !== '/')
		) {
		    $findroot = strpos($file, $_SERVER['DOCUMENT_ROOT']);
		    if (($findroot === false) || ($findroot > 1)) {
			$alt[] = htmlspecialchars_decode(urldecode($_SERVER['DOCUMENT_ROOT'].$file));
		    }
		}
		
		$protocol = 'http';
		if (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
		    $protocol .= 's';
		}
		
		$url = $file;
		if (preg_match('%^
			$url = $protocol.':'.str_replace(' ', '%20', $url);
		}
		$url = htmlspecialchars_decode($url);
		$alt[] = $url;
		
		if (preg_match('%^(https?):
		    && empty($_SERVER['HTTP_HOST'])
		    && empty($_SERVER['DOCUMENT_ROOT'])
		) {
			$urldata = parse_url($url);
			if (empty($urldata['query'])) {
				$host = $protocol.'://'.$_SERVER['HTTP_HOST'];
				if (strpos($url, $host) === 0) {
				    // convert URL to full server path
				    $tmp = str_replace($host, $_SERVER['DOCUMENT_ROOT'], $url);
				    $alt[] = htmlspecialchars_decode(urldecode($tmp));
				}
			}
		}
		
		if (isset($_SERVER['SCRIPT_URI'])
		    && !preg_match('%^(https?|ftp):
		    && !preg_match('%^
		) {
		    $urldata = @parse_url($_SERVER['SCRIPT_URI']);
		    $alt[] = $urldata['scheme'].'://'.$urldata['host'].(($file[0] == '/') ? '' : '/').$file;
		}
		
		$alt = array_unique($alt);
		foreach ($alt as $path) {
			if (!self::file_exists($path)) {
				continue;
			}
			$ret = @file_get_contents($path);
			if ( $ret != false ) {
			    return $ret;
			}
			
			if (!ini_get('allow_url_fopen')
				&& function_exists('curl_init')
				&& preg_match('%^(https?|ftp):
			) {
				
				$crs = curl_init();
				curl_setopt($crs, CURLOPT_URL, $path);
				curl_setopt($crs, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($crs, CURLOPT_FAILONERROR, true);
				curl_setopt($crs, CURLOPT_RETURNTRANSFER, true);
				if ((ini_get('open_basedir') == '') && (!ini_get('safe_mode'))) {
				    curl_setopt($crs, CURLOPT_FOLLOWLOCATION, true);
				}
				curl_setopt($crs, CURLOPT_CONNECTTIMEOUT, 5);
				curl_setopt($crs, CURLOPT_TIMEOUT, 30);
				curl_setopt($crs, CURLOPT_SSL_VERIFYPEER, TRUE);
				curl_setopt($crs, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($crs, CURLOPT_USERAGENT, 'tc-lib-file');
				$ret = curl_exec($crs);
				curl_close($crs);
				if ($ret !== false) {
					return $ret;
				}
			}
		}
		return false;
	}

	/**
	 * Get ULONG from string (Big Endian 32-bit unsigned integer).
	 * @param $str (string) string from where to extract value
	 * @param $offset (int) point from where to read the data
	 * @return int 32 bit value
	 * @author Nicola Asuni
	 * @since 5.2.000 (2010-06-02)
	 * @public static
	 */
	public static function _getULONG($str, $offset) {
		$v = unpack('Ni', substr($str, $offset, 4));
		return $v['i'];
	}

	/**
	 * Get USHORT from string (Big Endian 16-bit unsigned integer).
	 * @param $str (string) string from where to extract value
	 * @param $offset (int) point from where to read the data
	 * @return int 16 bit value
	 * @author Nicola Asuni
	 * @since 5.2.000 (2010-06-02)
	 * @public static
	 */
	public static function _getUSHORT($str, $offset) {
		$v = unpack('ni', substr($str, $offset, 2));
		return $v['i'];
	}

	/**
	 * Get SHORT from string (Big Endian 16-bit signed integer).
	 * @param $str (string) String from where to extract value.
	 * @param $offset (int) Point from where to read the data.
	 * @return int 16 bit value
	 * @author Nicola Asuni
	 * @since 5.2.000 (2010-06-02)
	 * @public static
	 */
	public static function _getSHORT($str, $offset) {
		$v = unpack('si', substr($str, $offset, 2));
		return $v['i'];
	}

	/**
	 * Get FWORD from string (Big Endian 16-bit signed integer).
	 * @param $str (string) String from where to extract value.
	 * @param $offset (int) Point from where to read the data.
	 * @return int 16 bit value
	 * @author Nicola Asuni
	 * @since 5.9.123 (2011-09-30)
	 * @public static
	 */
	public static function _getFWORD($str, $offset) {
		$v = self::_getUSHORT($str, $offset);
		if ($v > 0x7fff) {
			$v -= 0x10000;
		}
		return $v;
	}

	/**
	 * Get UFWORD from string (Big Endian 16-bit unsigned integer).
	 * @param $str (string) string from where to extract value
	 * @param $offset (int) point from where to read the data
	 * @return int 16 bit value
	 * @author Nicola Asuni
	 * @since 5.9.123 (2011-09-30)
	 * @public static
	 */
	public static function _getUFWORD($str, $offset) {
		$v = self::_getUSHORT($str, $offset);
		return $v;
	}

	/**
	 * Get FIXED from string (32-bit signed fixed-point number (16.16).
	 * @param $str (string) string from where to extract value
	 * @param $offset (int) point from where to read the data
	 * @return int 16 bit value
	 * @author Nicola Asuni
	 * @since 5.9.123 (2011-09-30)
	 * @public static
	 */
	public static function _getFIXED($str, $offset) {
		// mantissa
		$m = self::_getFWORD($str, $offset);
		// fraction
		$f = self::_getUSHORT($str, ($offset + 2));
		$v = floatval(''.$m.'.'.$f.'');
		return $v;
	}

	/**
	 * Get BYTE from string (8-bit unsigned integer).
	 * @param $str (string) String from where to extract value.
	 * @param $offset (int) Point from where to read the data.
	 * @return int 8 bit value
	 * @author Nicola Asuni
	 * @since 5.2.000 (2010-06-02)
	 * @public static
	 */
	public static function _getBYTE($str, $offset) {
		$v = unpack('Ci', substr($str, $offset, 1));
		return $v['i'];
	}
	/**
	 * Binary-safe and URL-safe file read.
	 * Reads up to length bytes from the file pointer referenced by handle. Reading stops as soon as one of the following conditions is met: length bytes have been read; EOF (end of file) is reached.
	 * @param $handle (resource)
	 * @param $length (int)
	 * @return Returns the read string or FALSE in case of error.
	 * @author Nicola Asuni
	 * @since 4.5.027 (2009-03-16)
	 * @public static
	 */
	public static function rfread($handle, $length) {
		$data = fread($handle, $length);
		if ($data === false) {
			return false;
		}
		$rest = ($length - strlen($data));
		if (($rest > 0) && !feof($handle)) {
			$data .= self::rfread($handle, $rest);
		}
		return $data;
	}

	/**
	 * Read a 4-byte (32 bit) integer from file.
	 * @param $f (string) file name.
	 * @return 4-byte integer
	 * @public static
	 */
	public static function _freadint($f) {
		$a = unpack('Ni', fread($f, 4));
		return $a['i'];
	}

	
	/**
	 * Array of page formats
	 * measures are calculated in this way: (inches * 72) or (millimeters * 72 / 25.4)
	 * @public static
	 */
	public static $page_formats = array(
		// ISO 216 A Series + 2 SIS 014711 extensions
		'A0'                     => array( 2383.937,  3370.394), 
		'A1'                     => array( 1683.780,  2383.937), 
		'A2'                     => array( 1190.551,  1683.780), 
		'A3'                     => array(  841.890,  1190.551), 
		'A4'                     => array(  595.276,   841.890), 
		'A5'                     => array(  419.528,   595.276), 
		'A6'                     => array(  297.638,   419.528), 
		'A7'                     => array(  209.764,   297.638), 
		'A8'                     => array(  147.402,   209.764), 
		'A9'                     => array(  104.882,   147.402), 
		'A10'                    => array(   73.701,   104.882), 
		'A11'                    => array(   51.024,    73.701), 
		'A12'                    => array(   36.850,    51.024), 
		// ISO 216 B Series + 2 SIS 014711 extensions
		'B0'                     => array( 2834.646,  4008.189), 
		'B1'                     => array( 2004.094,  2834.646), 
		'B2'                     => array( 1417.323,  2004.094), 
		'B3'                     => array( 1000.630,  1417.323), 
		'B4'                     => array(  708.661,  1000.630), 
		'B5'                     => array(  498.898,   708.661), 
		'B6'                     => array(  354.331,   498.898), 
		'B7'                     => array(  249.449,   354.331), 
		'B8'                     => array(  175.748,   249.449), 
		'B9'                     => array(  124.724,   175.748), 
		'B10'                    => array(   87.874,   124.724), 
		'B11'                    => array(   62.362,    87.874), 
		'B12'                    => array(   42.520,    62.362), 
		// ISO 216 C Series + 2 SIS 014711 extensions + 5 EXTENSION
		'C0'                     => array( 2599.370,  3676.535), 
		'C1'                     => array( 1836.850,  2599.370), 
		'C2'                     => array( 1298.268,  1836.850), 
		'C3'                     => array(  918.425,  1298.268), 
		'C4'                     => array(  649.134,   918.425), 
		'C5'                     => array(  459.213,   649.134), 
		'C6'                     => array(  323.150,   459.213), 
		'C7'                     => array(  229.606,   323.150), 
		'C8'                     => array(  161.575,   229.606), 
		'C9'                     => array(  113.386,   161.575), 
		'C10'                    => array(   79.370,   113.386), 
		'C11'                    => array(   56.693,    79.370), 
		'C12'                    => array(   39.685,    56.693), 
		'C76'                    => array(  229.606,   459.213), 
		'DL'                     => array(  311.811,   623.622), 
		'DLE'                    => array(  323.150,   637.795), 
		'DLX'                    => array(  340.158,   666.142), 
		'DLP'                    => array(  280.630,   595.276), 
		// SIS 014711 E Series
		'E0'                     => array( 2491.654,  3517.795), 
		'E1'                     => array( 1757.480,  2491.654), 
		'E2'                     => array( 1247.244,  1757.480), 
		'E3'                     => array(  878.740,  1247.244), 
		'E4'                     => array(  623.622,   878.740), 
		'E5'                     => array(  439.370,   623.622), 
		'E6'                     => array(  311.811,   439.370), 
		'E7'                     => array(  221.102,   311.811), 
		'E8'                     => array(  155.906,   221.102), 
		'E9'                     => array(  110.551,   155.906), 
		'E10'                    => array(   76.535,   110.551), 
		'E11'                    => array(   53.858,    76.535), 
		'E12'                    => array(   36.850,    53.858), 
		// SIS 014711 G Series
		'G0'                     => array( 2715.591,  3838.110), 
		'G1'                     => array( 1919.055,  2715.591), 
		'G2'                     => array( 1357.795,  1919.055), 
		'G3'                     => array(  958.110,  1357.795), 
		'G4'                     => array(  677.480,   958.110), 
		'G5'                     => array(  479.055,   677.480), 
		'G6'                     => array(  337.323,   479.055), 
		'G7'                     => array(  238.110,   337.323), 
		'G8'                     => array(  167.244,   238.110), 
		'G9'                     => array(  119.055,   167.244), 
		'G10'                    => array(   82.205,   119.055), 
		'G11'                    => array(   59.528,    82.205), 
		'G12'                    => array(   39.685,    59.528), 
		// ISO Press
		'RA0'                    => array( 2437.795,  3458.268), 
		'RA1'                    => array( 1729.134,  2437.795), 
		'RA2'                    => array( 1218.898,  1729.134), 
		'RA3'                    => array(  864.567,  1218.898), 
		'RA4'                    => array(  609.449,   864.567), 
		'SRA0'                   => array( 2551.181,  3628.346), 
		'SRA1'                   => array( 1814.173,  2551.181), 
		'SRA2'                   => array( 1275.591,  1814.173), 
		'SRA3'                   => array(  907.087,  1275.591), 
		'SRA4'                   => array(  637.795,   907.087), 
		// German DIN 476
		'4A0'                    => array( 4767.874,  6740.787), 
		'2A0'                    => array( 3370.394,  4767.874), 
		// Variations on the ISO Standard
		'A2_EXTRA'               => array( 1261.417,  1754.646), 
		'A3+'                    => array(  932.598,  1369.134), 
		'A3_EXTRA'               => array(  912.756,  1261.417), 
		'A3_SUPER'               => array(  864.567,  1440.000), 
		'SUPER_A3'               => array(  864.567,  1380.472), 
		'A4_EXTRA'               => array(  666.142,   912.756), 
		'A4_SUPER'               => array(  649.134,   912.756), 
		'SUPER_A4'               => array(  643.465,  1009.134), 
		'A4_LONG'                => array(  595.276,   986.457), 
		'F4'                     => array(  595.276,   935.433), 
		'SO_B5_EXTRA'            => array(  572.598,   782.362), 
		'A5_EXTRA'               => array(  490.394,   666.142), 
		// ANSI Series
		'ANSI_E'                 => array( 2448.000,  3168.000), 
		'ANSI_D'                 => array( 1584.000,  2448.000), 
		'ANSI_C'                 => array( 1224.000,  1584.000), 
		'ANSI_B'                 => array(  792.000,  1224.000), 
		'ANSI_A'                 => array(  612.000,   792.000), 
		// Traditional 'Loose' North American Paper Sizes
		'USLEDGER'               => array( 1224.000,   792.000), 
		'LEDGER'                 => array( 1224.000,   792.000), 
		'ORGANIZERK'             => array(  792.000,  1224.000), 
		'BIBLE'                  => array(  792.000,  1224.000), 
		'USTABLOID'              => array(  792.000,  1224.000), 
		'TABLOID'                => array(  792.000,  1224.000), 
		'ORGANIZERM'             => array(  612.000,   792.000), 
		'USLETTER'               => array(  612.000,   792.000), 
		'LETTER'                 => array(  612.000,   792.000), 
		'USLEGAL'                => array(  612.000,  1008.000), 
		'LEGAL'                  => array(  612.000,  1008.000), 
		'GOVERNMENTLETTER'       => array(  576.000,   756.000), 
		'GLETTER'                => array(  576.000,   756.000), 
		'JUNIORLEGAL'            => array(  576.000,   360.000), 
		'JLEGAL'                 => array(  576.000,   360.000), 
		// Other North American Paper Sizes
		'QUADDEMY'               => array( 2520.000,  3240.000), 
		'SUPER_B'                => array(  936.000,  1368.000), 
		'QUARTO'                 => array(  648.000,   792.000), 
		'GOVERNMENTLEGAL'        => array(  612.000,   936.000), 
		'FOLIO'                  => array(  612.000,   936.000), 
		'MONARCH'                => array(  522.000,   756.000), 
		'EXECUTIVE'              => array(  522.000,   756.000), 
		'ORGANIZERL'             => array(  396.000,   612.000), 
		'STATEMENT'              => array(  396.000,   612.000), 
		'MEMO'                   => array(  396.000,   612.000), 
		'FOOLSCAP'               => array(  595.440,   936.000), 
		'COMPACT'                => array(  306.000,   486.000), 
		'ORGANIZERJ'             => array(  198.000,   360.000), 
		// Canadian standard CAN 2-9.60M
		'P1'                     => array( 1587.402,  2437.795), 
		'P2'                     => array( 1218.898,  1587.402), 
		'P3'                     => array(  793.701,  1218.898), 
		'P4'                     => array(  609.449,   793.701), 
		'P5'                     => array(  396.850,   609.449), 
		'P6'                     => array(  303.307,   396.850), 
		// North American Architectural Sizes
		'ARCH_E'                 => array( 2592.000,  3456.000), 
		'ARCH_E1'                => array( 2160.000,  3024.000), 
		'ARCH_D'                 => array( 1728.000,  2592.000), 
		'BROADSHEET'             => array( 1296.000,  1728.000), 
		'ARCH_C'                 => array( 1296.000,  1728.000), 
		'ARCH_B'                 => array(  864.000,  1296.000), 
		'ARCH_A'                 => array(  648.000,   864.000), 
		
		
		'ANNENV_A2'              => array(  314.640,   414.000), 
		'ANNENV_A6'              => array(  342.000,   468.000), 
		'ANNENV_A7'              => array(  378.000,   522.000), 
		'ANNENV_A8'              => array(  396.000,   584.640), 
		'ANNENV_A10'             => array(  450.000,   692.640), 
		'ANNENV_SLIM'            => array(  278.640,   638.640), 
		
		'COMMENV_N6_1/4'         => array(  252.000,   432.000), 
		'COMMENV_N6_3/4'         => array(  260.640,   468.000), 
		'COMMENV_N8'             => array(  278.640,   540.000), 
		'COMMENV_N9'             => array(  278.640,   638.640), 
		'COMMENV_N10'            => array(  296.640,   684.000), 
		'COMMENV_N11'            => array(  324.000,   746.640), 
		'COMMENV_N12'            => array(  342.000,   792.000), 
		'COMMENV_N14'            => array(  360.000,   828.000), 
		
		'CATENV_N1'              => array(  432.000,   648.000), 
		'CATENV_N1_3/4'          => array(  468.000,   684.000), 
		'CATENV_N2'              => array(  468.000,   720.000), 
		'CATENV_N3'              => array(  504.000,   720.000), 
		'CATENV_N6'              => array(  540.000,   756.000), 
		'CATENV_N7'              => array(  576.000,   792.000), 
		'CATENV_N8'              => array(  594.000,   810.000), 
		'CATENV_N9_1/2'          => array(  612.000,   756.000), 
		'CATENV_N9_3/4'          => array(  630.000,   810.000), 
		'CATENV_N10_1/2'         => array(  648.000,   864.000), 
		'CATENV_N12_1/2'         => array(  684.000,   900.000), 
		'CATENV_N13_1/2'         => array(  720.000,   936.000), 
		'CATENV_N14_1/4'         => array(  810.000,   882.000), 
		'CATENV_N14_1/2'         => array(  828.000,  1044.000), 
		// Japanese (JIS P 0138-61) Standard B-Series
		'JIS_B0'                 => array( 2919.685,  4127.244), 
		'JIS_B1'                 => array( 2063.622,  2919.685), 
		'JIS_B2'                 => array( 1459.843,  2063.622), 
		'JIS_B3'                 => array( 1031.811,  1459.843), 
		'JIS_B4'                 => array(  728.504,  1031.811), 
		'JIS_B5'                 => array(  515.906,   728.504), 
		'JIS_B6'                 => array(  362.835,   515.906), 
		'JIS_B7'                 => array(  257.953,   362.835), 
		'JIS_B8'                 => array(  181.417,   257.953), 
		'JIS_B9'                 => array(  127.559,   181.417), 
		'JIS_B10'                => array(   90.709,   127.559), 
		'JIS_B11'                => array(   62.362,    90.709), 
		'JIS_B12'                => array(   45.354,    62.362), 
		// PA Series
		'PA0'                    => array( 2381.102,  3174.803), 
		'PA1'                    => array( 1587.402,  2381.102), 
		'PA2'                    => array( 1190.551,  1587.402), 
		'PA3'                    => array(  793.701,  1190.551), 
		'PA4'                    => array(  595.276,   793.701), 
		'PA5'                    => array(  396.850,   595.276), 
		'PA6'                    => array(  297.638,   396.850), 
		'PA7'                    => array(  198.425,   297.638), 
		'PA8'                    => array(  147.402,   198.425), 
		'PA9'                    => array(   99.213,   147.402), 
		'PA10'                   => array(   73.701,    99.213), 
		// Standard Photographic Print Sizes
		'PASSPORT_PHOTO'         => array(   99.213,   127.559), 
		'E'                      => array(  233.858,   340.157), 
		'L'                      => array(  252.283,   360.000), 
		'3R'                     => array(  252.283,   360.000), 
		'KG'                     => array(  289.134,   430.866), 
		'4R'                     => array(  289.134,   430.866), 
		'4D'                     => array(  340.157,   430.866), 
		'2L'                     => array(  360.000,   504.567), 
		'5R'                     => array(  360.000,   504.567), 
		'8P'                     => array(  430.866,   575.433), 
		'6R'                     => array(  430.866,   575.433), 
		'6P'                     => array(  575.433,   720.000), 
		'8R'                     => array(  575.433,   720.000), 
		'6PW'                    => array(  575.433,   864.567), 
		'S8R'                    => array(  575.433,   864.567), 
		'4P'                     => array(  720.000,   864.567), 
		'10R'                    => array(  720.000,   864.567), 
		'4PW'                    => array(  720.000,  1080.000), 
		'S10R'                   => array(  720.000,  1080.000), 
		'11R'                    => array(  790.866,  1009.134), 
		'S11R'                   => array(  790.866,  1224.567), 
		'12R'                    => array(  864.567,  1080.000), 
		'S12R'                   => array(  864.567,  1292.598), 
		// Common Newspaper Sizes
		'NEWSPAPER_BROADSHEET'   => array( 2125.984,  1700.787), 
		'NEWSPAPER_BERLINER'     => array( 1332.283,   892.913), 
		'NEWSPAPER_TABLOID'      => array( 1218.898,   793.701), 
		'NEWSPAPER_COMPACT'      => array( 1218.898,   793.701), 
		// Business Cards
		'CREDIT_CARD'            => array(  153.014,   242.646), 
		'BUSINESS_CARD'          => array(  153.014,   242.646), 
		'BUSINESS_CARD_ISO7810'  => array(  153.014,   242.646), 
		'BUSINESS_CARD_ISO216'   => array(  147.402,   209.764), 
		'BUSINESS_CARD_IT'       => array(  155.906,   240.945), 
		'BUSINESS_CARD_UK'       => array(  155.906,   240.945), 
		'BUSINESS_CARD_FR'       => array(  155.906,   240.945), 
		'BUSINESS_CARD_DE'       => array(  155.906,   240.945), 
		'BUSINESS_CARD_ES'       => array(  155.906,   240.945), 
		'BUSINESS_CARD_CA'       => array(  144.567,   252.283), 
		'BUSINESS_CARD_US'       => array(  144.567,   252.283), 
		'BUSINESS_CARD_JP'       => array(  155.906,   257.953), 
		'BUSINESS_CARD_HK'       => array(  153.071,   255.118), 
		'BUSINESS_CARD_AU'       => array(  155.906,   255.118), 
		'BUSINESS_CARD_DK'       => array(  155.906,   255.118), 
		'BUSINESS_CARD_SE'       => array(  155.906,   255.118), 
		'BUSINESS_CARD_RU'       => array(  141.732,   255.118), 
		'BUSINESS_CARD_CZ'       => array(  141.732,   255.118), 
		'BUSINESS_CARD_FI'       => array(  141.732,   255.118), 
		'BUSINESS_CARD_HU'       => array(  141.732,   255.118), 
		'BUSINESS_CARD_IL'       => array(  141.732,   255.118), 
		// Billboards
		'4SHEET'                 => array( 2880.000,  4320.000), 
		'6SHEET'                 => array( 3401.575,  5102.362), 
		'12SHEET'                => array( 8640.000,  4320.000), 
		'16SHEET'                => array( 5760.000,  8640.000), 
		'32SHEET'                => array(11520.000,  8640.000), 
		'48SHEET'                => array(17280.000,  8640.000), 
		'64SHEET'                => array(23040.000,  8640.000), 
		'96SHEET'                => array(34560.000,  8640.000), 
		
		
		'EN_EMPEROR'             => array( 3456.000,  5184.000), 
		'EN_ANTIQUARIAN'         => array( 2232.000,  3816.000), 
		'EN_GRAND_EAGLE'         => array( 2070.000,  3024.000), 
		'EN_DOUBLE_ELEPHANT'     => array( 1926.000,  2880.000), 
		'EN_ATLAS'               => array( 1872.000,  2448.000), 
		'EN_COLOMBIER'           => array( 1692.000,  2484.000), 
		'EN_ELEPHANT'            => array( 1656.000,  2016.000), 
		'EN_DOUBLE_DEMY'         => array( 1620.000,  2556.000), 
		'EN_IMPERIAL'            => array( 1584.000,  2160.000), 
		'EN_PRINCESS'            => array( 1548.000,  2016.000), 
		'EN_CARTRIDGE'           => array( 1512.000,  1872.000), 
		'EN_DOUBLE_LARGE_POST'   => array( 1512.000,  2376.000), 
		'EN_ROYAL'               => array( 1440.000,  1800.000), 
		'EN_SHEET'               => array( 1404.000,  1692.000), 
		'EN_HALF_POST'           => array( 1404.000,  1692.000), 
		'EN_SUPER_ROYAL'         => array( 1368.000,  1944.000), 
		'EN_DOUBLE_POST'         => array( 1368.000,  2196.000), 
		'EN_MEDIUM'              => array( 1260.000,  1656.000), 
		'EN_DEMY'                => array( 1260.000,  1620.000), 
		'EN_LARGE_POST'          => array( 1188.000,  1512.000), 
		'EN_COPY_DRAUGHT'        => array( 1152.000,  1440.000), 
		'EN_POST'                => array( 1116.000,  1386.000), 
		'EN_CROWN'               => array( 1080.000,  1440.000), 
		'EN_PINCHED_POST'        => array( 1062.000,  1332.000), 
		'EN_BRIEF'               => array(  972.000,  1152.000), 
		'EN_FOOLSCAP'            => array(  972.000,  1224.000), 
		'EN_SMALL_FOOLSCAP'      => array(  954.000,  1188.000), 
		'EN_POTT'                => array(  900.000,  1080.000), 
		
		'BE_GRAND_AIGLE'         => array( 1984.252,  2948.031), 
		'BE_COLOMBIER'           => array( 1757.480,  2409.449), 
		'BE_DOUBLE_CARRE'        => array( 1757.480,  2607.874), 
		'BE_ELEPHANT'            => array( 1746.142,  2182.677), 
		'BE_PETIT_AIGLE'         => array( 1700.787,  2381.102), 
		'BE_GRAND_JESUS'         => array( 1559.055,  2069.291), 
		'BE_JESUS'               => array( 1530.709,  2069.291), 
		'BE_RAISIN'              => array( 1417.323,  1842.520), 
		'BE_GRAND_MEDIAN'        => array( 1303.937,  1714.961), 
		'BE_DOUBLE_POSTE'        => array( 1233.071,  1601.575), 
		'BE_COQUILLE'            => array( 1218.898,  1587.402), 
		'BE_PETIT_MEDIAN'        => array( 1176.378,  1502.362), 
		'BE_RUCHE'               => array( 1020.472,  1303.937), 
		'BE_PROPATRIA'           => array(  977.953,  1218.898), 
		'BE_LYS'                 => array(  898.583,  1125.354), 
		'BE_POT'                 => array(  870.236,  1088.504), 
		'BE_ROSETTE'             => array(  765.354,   983.622), 
		
		'FR_UNIVERS'             => array( 2834.646,  3685.039), 
		'FR_DOUBLE_COLOMBIER'    => array( 2551.181,  3571.654), 
		'FR_GRANDE_MONDE'        => array( 2551.181,  3571.654), 
		'FR_DOUBLE_SOLEIL'       => array( 2267.717,  3401.575), 
		'FR_DOUBLE_JESUS'        => array( 2154.331,  3174.803), 
		'FR_GRAND_AIGLE'         => array( 2125.984,  3004.724), 
		'FR_PETIT_AIGLE'         => array( 1984.252,  2664.567), 
		'FR_DOUBLE_RAISIN'       => array( 1842.520,  2834.646), 
		'FR_JOURNAL'             => array( 1842.520,  2664.567), 
		'FR_COLOMBIER_AFFICHE'   => array( 1785.827,  2551.181), 
		'FR_DOUBLE_CAVALIER'     => array( 1757.480,  2607.874), 
		'FR_CLOCHE'              => array( 1700.787,  2267.717), 
		'FR_SOLEIL'              => array( 1700.787,  2267.717), 
		'FR_DOUBLE_CARRE'        => array( 1587.402,  2551.181), 
		'FR_DOUBLE_COQUILLE'     => array( 1587.402,  2494.488), 
		'FR_JESUS'               => array( 1587.402,  2154.331), 
		'FR_RAISIN'              => array( 1417.323,  1842.520), 
		'FR_CAVALIER'            => array( 1303.937,  1757.480), 
		'FR_DOUBLE_COURONNE'     => array( 1303.937,  2040.945), 
		'FR_CARRE'               => array( 1275.591,  1587.402), 
		'FR_COQUILLE'            => array( 1247.244,  1587.402), 
		'FR_DOUBLE_TELLIERE'     => array( 1247.244,  1927.559), 
		'FR_DOUBLE_CLOCHE'       => array( 1133.858,  1700.787), 
		'FR_DOUBLE_POT'          => array( 1133.858,  1757.480), 
		'FR_ECU'                 => array( 1133.858,  1474.016), 
		'FR_COURONNE'            => array( 1020.472,  1303.937), 
		'FR_TELLIERE'            => array(  963.780,  1247.244), 
		'FR_POT'                 => array(  878.740,  1133.858), 
	);


	/**
	 * Get page dimensions from format name.
	 * @param $format (mixed) The format name @see self::$page_format<ul>
	 * @return array containing page width and height in points
	 * @since 5.0.010 (2010-05-17)
	 * @public static
	 */
	public static function getPageSizeFromFormat($format) {
		if (isset(self::$page_formats[$format])) {
			return self::$page_formats[$format];
		}
		return self::$page_formats['A4'];
	}

	/**
	 * Set page boundaries.
	 * @param $page (int) page number
	 * @param $type (string) valid values are: <ul><li>'MediaBox' : the boundaries of the physical medium on which the page shall be displayed or printed;</li><li>'CropBox' : the visible region of default user space;</li><li>'BleedBox' : the region to which the contents of the page shall be clipped when output in a production environment;</li><li>'TrimBox' : the intended dimensions of the finished page after trimming;</li><li>'ArtBox' : the page's meaningful content (including potential white space).</li></ul>
	 * @param $llx (float) lower-left x coordinate in user units.
	 * @param $lly (float) lower-left y coordinate in user units.
	 * @param $urx (float) upper-right x coordinate in user units.
	 * @param $ury (float) upper-right y coordinate in user units.
	 * @param $points (boolean) If true uses user units as unit of measure, otherwise uses PDF points.
	 * @param $k (float) Scale factor (number of points in user unit).
	 * @param $pagedim (array) Array of page dimensions.
	 * @return pagedim array of page dimensions.
	 * @since 5.0.010 (2010-05-17)
	 * @public static
	 */
	public static function setPageBoxes($page, $type, $llx, $lly, $urx, $ury, $points=false, $k, $pagedim=array()) {
		if (!isset($pagedim[$page])) {
			// initialize array
			$pagedim[$page] = array();
		}
		if (!in_array($type, self::$pageboxes)) {
			return;
		}
		if ($points) {
			$k = 1;
		}
		$pagedim[$page][$type]['llx'] = ($llx * $k);
		$pagedim[$page][$type]['lly'] = ($lly * $k);
		$pagedim[$page][$type]['urx'] = ($urx * $k);
		$pagedim[$page][$type]['ury'] = ($ury * $k);
		return $pagedim;
	}

	/**
	 * Swap X and Y coordinates of page boxes (change page boxes orientation).
	 * @param $page (int) page number
	 * @param $pagedim (array) Array of page dimensions.
	 * @return pagedim array of page dimensions.
	 * @since 5.0.010 (2010-05-17)
	 * @public static
	 */
	public static function swapPageBoxCoordinates($page, $pagedim) {
		foreach (self::$pageboxes as $type) {
			// swap X and Y coordinates
			if (isset($pagedim[$page][$type])) {
				$tmp = $pagedim[$page][$type]['llx'];
				$pagedim[$page][$type]['llx'] = $pagedim[$page][$type]['lly'];
				$pagedim[$page][$type]['lly'] = $tmp;
				$tmp = $pagedim[$page][$type]['urx'];
				$pagedim[$page][$type]['urx'] = $pagedim[$page][$type]['ury'];
				$pagedim[$page][$type]['ury'] = $tmp;
			}
		}
		return $pagedim;
	}

	/**
	 * Get the canonical page layout mode.
	 * @param $layout (string) The page layout. Possible values are:<ul><li>SinglePage Display one page at a time</li><li>OneColumn Display the pages in one column</li><li>TwoColumnLeft Display the pages in two columns, with odd-numbered pages on the left</li><li>TwoColumnRight Display the pages in two columns, with odd-numbered pages on the right</li><li>TwoPageLeft (PDF 1.5) Display the pages two at a time, with odd-numbered pages on the left</li><li>TwoPageRight (PDF 1.5) Display the pages two at a time, with odd-numbered pages on the right</li></ul>
	 * @return (string) Canonical page layout name.
	 * @public static
	 */
	public static function getPageLayoutMode($layout='SinglePage') {
		switch ($layout) {
			case 'default':
			case 'single':
			case 'SinglePage': {
				$layout_mode = 'SinglePage';
				break;
			}
			case 'continuous':
			case 'OneColumn': {
				$layout_mode = 'OneColumn';
				break;
			}
			case 'two':
			case 'TwoColumnLeft': {
				$layout_mode = 'TwoColumnLeft';
				break;
			}
			case 'TwoColumnRight': {
				$layout_mode = 'TwoColumnRight';
				break;
			}
			case 'TwoPageLeft': {
				$layout_mode = 'TwoPageLeft';
				break;
			}
			case 'TwoPageRight': {
				$layout_mode = 'TwoPageRight';
				break;
			}
			default: {
				$layout_mode = 'SinglePage';
			}
		}
		return $layout_mode;
	}

	/**
	 * Get the canonical page layout mode.
	 * @param $mode (string) A name object specifying how the document should be displayed when opened:<ul><li>UseNone Neither document outline nor thumbnail images visible</li><li>UseOutlines Document outline visible</li><li>UseThumbs Thumbnail images visible</li><li>FullScreen Full-screen mode, with no menu bar, window controls, or any other window visible</li><li>UseOC (PDF 1.5) Optional content group panel visible</li><li>UseAttachments (PDF 1.6) Attachments panel visible</li></ul>
	 * @return (string) Canonical page mode name.
	 * @public static
	 */
	public static function getPageMode($mode='UseNone') {
		switch ($mode) {
			case 'UseNone': {
				$page_mode = 'UseNone';
				break;
			}
			case 'UseOutlines': {
				$page_mode = 'UseOutlines';
				break;
			}
			case 'UseThumbs': {
				$page_mode = 'UseThumbs';
				break;
			}
			case 'FullScreen': {
				$page_mode = 'FullScreen';
				break;
			}
			case 'UseOC': {
				$page_mode = 'UseOC';
				break;
			}
			case '': {
				$page_mode = 'UseAttachments';
				break;
			}
			default: {
				$page_mode = 'UseNone';
			}
		}
		return $page_mode;
	}


} // END OF TCPDF_STATIC CLASS


// END OF FILE

