<?php

namespace FBWAScrapper\Parser;

class GetString
{
	public static function GetString($start, $end, $content)
	{
		preg_match('|'.$start.'(.*?)'.$end.'|m', $content, $result);
		return $result;
	}
}