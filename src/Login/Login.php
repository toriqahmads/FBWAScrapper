<?php

namespace FBWAScrapper\Login;
use FBWAScrapper\Curl\Curl;
use FBWAScrapper\Parser\GetString;

class Login
{
	private $email;
	private $pass;
	private $curl;
	private $LoginPage;
	private $url;
	private $body;

	public function __construct($email, $pass, $socks = '')
	{
		$this->email = $email;
		$this->pass = $pass;
		$this->url = "https://mbasic.facebook.com/login/device-based/regular/login/";
		$this->curl = new Curl($socks);
	}

	protected function LoginPage()
	{
		$this->curl->SetURL("https://mbasic.facebook.com/");
		$this->curl->Curl();
		$this->LoginPage = $this->curl->GetBody();
	}

	public function GetLsd()
	{
		$lsd = GetString::GetString('<input type="hidden" name="lsd" value="', '" autocomplete="off" />', $this->LoginPage);

		return $lsd[1];
	}

	public function GetJazoest()
	{
		$jazoest = GetString::GetString('<input type="hidden" name="jazoest" value="', '" autocomplete="off" />', $this->LoginPage);

		return $jazoest[1];
	}

	public function GetMTS()
	{
		$mts = GetString::GetString('<input type="hidden" name="m_ts" value="', '" />', $this->LoginPage);

		return $mts[1];
	}

	public function GetLI()
	{
		$li = GetString::GetString('<input type="hidden" name="li" value="', '" />', $this->LoginPage);

		return $li[1];
	}

	public function GetTryNumber()
	{
		$try = GetString::GetString('<input type="hidden" name="try_number" value="', '" />', $this->LoginPage);

		return $try[1];
	}

	public function GetUnrecognize()
	{
		$ut = GetString::GetString('<input type="hidden" name="unrecognized_tries" value="', '" />', $this->LoginPage);

		return $ut[1];
	}

	public function GetDtsg()
	{
		$dtsg = GetString::GetString('<input type="hidden" name="fb_dtsg" value="', '" autocomplete="off" />', $this->LoginPage);

		return $dtsg[1];
	}

	public function GetFlow()
	{
		$flow = GetString::GetString('<input type="hidden" name="flow" value="', '" />', $this->LoginPage);

		return $flow[1];
	}

	private function BuildPostData($data)
	{
		return http_build_query($data);
	}

	private function BuildHeader()
	{
		$header = array();
		$header[] = "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
		$header[] = "accept-language: en-US,en;q=0.9";
		$header[] = "cache-control: max-age=0";
		$header[] = "content-type: application/x-www-form-urlencoded";

		return $header;
	}

	public function Login()
	{
		if($this->isLoggedIn() == false)
		{
			$this->LoginPage();

			$data = array('lsd' => $this->GetLsd(), 'jazoest' => $this->GetJazoest(), 'm_ts' => $this->GetMTS(), 'li' => $this->GetLI(), 'try_number' => $this->GetTryNumber(), 'unrecognized_tries' => $this->GetUnrecognize(), 'email' => $this->email, 'pass' => $this->pass, 'login' => "Masuk");

			$this->curl->SetURL($this->url);
			$this->curl->SetPostData($this->BuildPostData($data));
			$this->curl->SetRequestHeader($this->BuildHeader());
		}
		else
		{
			$this->curl->SetURL($this->url);
		}

		return $this->LoginPost();
	}

	public function LoginPost()
	{		
		$this->curl->Curl();
		$this->body = $this->curl->GetBody();
		$this->LoginPage = $this->curl->GetBody();
		if(strpos($this->body, '<a href="/home.php') !== false)
		{
			$response = array("response" => "ok", "status" => "logged in");
		}

		if(strpos($this->body, 'action="/login/device-based/update-nonce/') !== false)
		{
			$response = $this->SaveDevice();
		}

		return $this->toJson($response);
	}

	public function SaveDevice()
	{
		$data = array('fb_dtsg' => $this->GetDtsg(), 'jazoest' => $this->GetJazoest(), 'flow' => $this->GetFlow(), 'next' => '', 'nux_source' => 'regular_login');
		$this->curl->SetURL("https://mbasic.facebook.com/login/device-based/update-nonce/");
		$this->curl->SetPostData($this->BuildPostData($data));
		$this->curl->SetRequestHeader($this->BuildHeader());
		$this->curl->Curl();
		$this->body = $this->curl->GetBody();
		if(strpos($this->body, '<a href="/home.php') !== false)
		{
			$response = array("response" => "ok", "status" => "logged in");
		}
		else
		{
			$response = array("response" => "fail", "status" => "fail to answer challenge");
		}

		return $response;
	}

	public function GetBody()
	{
		return $this->body;
	}

	private function toJson($content)
	{
		return json_encode($content);
	}

	public function isLoggedIn()
	{
		$this->LoginPage();
		if(strpos($this->LoginPage, '<a href="/home.php') !== false)
		{
			return true;
		}
		return false;
	}
}