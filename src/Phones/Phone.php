<?php
namespace FBWAScrapper\Phones;
use FBWAScrapper\Curl\Curl;
use FBWAScrapper\Groups\Group;
use FBWAScrapper\Parser\Phone as PhoneParser;
use FBWAScrapper\Parser\GetString;
use voku\helper\HtmlDomParser;

class Phone
{
	private $url;
	private $curl;
	private $page;
	private $groups = array();
	private $phones = array();
	private $nextUrl;
	private $groupLink;

	public function __construct($socks = '')
	{
		$this->curl = new Curl($socks);
		$group = new Group;
		$group->ParseGroupList();
		$this->groups = json_decode($group->GetGroupList(), true);
	}

	public function VisitGroup()
	{
		$this->curl->SetUrl($this->url);
		$this->curl->Curl();
		$this->page = $this->curl->GetBody();
	}

	public function ExtractPhones()
	{
		$phones = PhoneParser::GetPhones($this->page);
		foreach($phones[0] as $phone) 
		{
			if(!in_array($phone, $this->phones))
				$this->phones[] = $phone;
		}
	}

	public function LoadMore()
	{
		$url = GetString::GetString('bacr=', '"><span>', $this->page);
		$this->nextUrl = $this->groupLink."?bacr=".$url[1];
	}

	public function Phones()
	{
		foreach ($this->groups as $group) 
		{
			$this->url = $group['link'];
			$this->groupLink = $group['link'];
			$this->VisitGroup();
			$this->ExtractPhones();
			for($i=0; $i<5; $i++)
			{
				$this->LoadMore();
				$this->url = $this->nextUrl;
				$this->VisitGroup();
				$this->ExtractPhones();
			}
		}
	}

	public function GetPhones()
	{
		return json_encode($this->phones);
	}

	public function GetNextUrl()
	{
		return $this->nextUrl;
	}
}