<?php

namespace FBWAScrapper\Logout;
use FBWAScrapper\Curl\Curl;
use FBWAScrapper\Parser\GetString;

class Logout
{
	private $url;
	private $curl;
	private $page;

	public function __construct($socks = '')
	{
		$this->url = "https://mbasic.facebook.com/";
		$this->curl = new Curl($socks);
	}

	public function GetLogoutURL()
	{
		$this->curl->SetURL($this->url);
		$this->curl->Curl();
		$this->page = $this->curl->GetBody();
		$url = GetString::GetString('href="/logout.php', '" id="mbasic_logout_button">', $this->page);

		return "https://mbasic.facebook.com/logout.php".$url[1];
	}

	public function Logout()
	{
		$this->curl->SetURL($this->GetLogoutURL());
		$this->curl->Curl();
		$this->page = $this->curl->GetBody();

		if(strpos($this->page, "login") !== false)
		{
			unlink(__DIR__ . "/../Curl/cookie.txt");
			return json_encode(array('response' => 'ok', 'status' => 'logged out'));
		}
		return json_encode(array('response' => 'fail', 'status' => 'not logged out'));
	}
}