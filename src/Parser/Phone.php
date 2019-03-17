<?php

namespace FBWAScrapper\Parser;

class Phone
{
	public static function GetPhones($content)
	{
		preg_match_all('/\b[\s()\d-]{10,13}\d\b/mi', $content, $result);
		return $result;
	}
}