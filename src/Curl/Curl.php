<?php

namespace FBWAScrapper\Curl;

class Curl
{
	private $c;
	private $response;
	private $httpcode;
	private $RequestHeader = array();
	private $ResponseHeader;
	private $body;
	private $error;
	private $post;
	private $url;
	private $socks;

	public function __construct($socks = '')
	{
		$this->socks = $socks;
	}

	public function Curl()
	{
		$this->c = curl_init($this->url);
		curl_setopt($this->c, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0");
		curl_setopt($this->c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->c, CURLOPT_HEADER, true);
		if(!empty($this->header))
		{
			curl_setopt($this->c, CURLOPT_HTTPHEADER, $this->RequestHeader);
		}
		if(!empty($this->socks))
		{
			curl_setopt($this->c, CURLOPT_PROXY, $this->socks);
	        curl_setopt($this->c, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		}
		curl_setopt($this->c, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/cookie.txt");
	    curl_setopt($this->c, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/cookie.txt");
	    if(!empty($this->post))
	    {
	    	curl_setopt($this->c, CURLOPT_POSTFIELDS, $this->post);
	    	curl_setopt($this->c, CURLOPT_POST, true);
	    }

	    $this->response = curl_exec($this->c);
	    $this->httpcode = curl_getinfo($this->c);
	    $this->error = curl_error($this->c);
	    
	    if(!$this->httpcode) return false; 
	    else
	    {
	        $this->header = substr($this->response, 0, curl_getinfo($this->c, CURLINFO_HEADER_SIZE));
	        $this->body = substr($this->response, curl_getinfo($this->c, CURLINFO_HEADER_SIZE));
	    }
	}

	public function GetResponse()
	{
		return $this->response;
	}

	public function GetHttpCode()
	{
		return $this->httpcode;
	}

	public function GetResponseHeader()
	{
		return $this->ResponseHeader;
	}

	public function GetBody()
	{
		return $this->body;
	}

	public function GetError()
	{
		return $this->error;
	}

	public function SetRequestHeader($header = array())
	{
		$this->RequestHeader = $header;
	}

	public function SetPostData($post)
	{
		$this->post = $post;
		return $this;
	}

	public function SetURL($url)
	{
		$this->url = $url;
	}

	public function toJson()
	{
		$this->post = json_encode($this->post);
	}

	public function toURLEncode()
	{
		$this->post = urlencode($this->post);
	}
}
?>