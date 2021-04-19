<?php

namespace App\Common;

class Emoji
{
	public static function encode($s)
	{
		return self::convertEmoji($s, 'encode');
	}

	public static function decode($s)
	{
		return self::convertEmoji($s, 'decode');
	}

	private static function convertEmoji($s, $option)
	{
		if ($option == 'encode') {
			return preg_replace_callback(
				'/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{1F000}-\x{1FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F9FF}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F9FF}][\x{1F000}-\x{1FEFF}]?/u', 
				['self', 'encodeEmoji'], 
				$s
			);
		} else {
			return preg_replace_callback(
				'/(\\\u[0-9a-f]{4})+/',
				['self', 'decodeEmoji'], 
				$s
			);
		}
	}

	private static function encodeEmoji($match)
	{
		return str_replace(
			['[', ']', '"'], 
			'', 
			json_encode($match)
		);
	}
	
	private static function decodeEmoji($s)
	{
		if (!$s) {
			return '';
		}
		$s = $s[0];
		$decode = json_decode($s, true);
		if ($decode) {
			return $decode;
		}	
		$s = '["' . $s . '"]';
		$decode = json_decode($s);
		if (count($decode) == 1) {
		   return $decode[0];
		}
		return $s;
	}
}