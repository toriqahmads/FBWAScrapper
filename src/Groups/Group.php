<?php
namespace FBWAScrapper\Groups;
use FBWAScrapper\Curl\Curl;
use voku\helper\HtmlDomParser;

class Group
{
	private $url;
	private $curl;
	private $page;
	private $groups = array();

	public function __construct($socks = '')
	{
		$this->url = "https://mbasic.facebook.com/groups/?seemore";
		$this->curl = new Curl($socks);
	}

	protected function GetGroupTable()
	{
		$this->curl->SetURL($this->url);
		$this->curl->Curl();
		$this->page = $this->curl->GetBody();
	}

	public function ParseGroupList()
	{
		$this->GetGroupTable();
		$html = HtmlDomParser::str_get_html($this->page);

		foreach ($html->find('table .bv') as $table) 
		{
			foreach ($table->find('tbody') as $tbody) 
			{
				foreach ($tbody->find('tr') as $tr) 
				{
					foreach ($tr->find('td') as $td) 
					{
						foreach ($td->find('a') as $a) 
						{
							$this->groups[] = array('group_name' => $a->innertext, 'link' => 'https://mbasic.facebook.com' . $a->href);
						}
					}
				}
			}
		}
	}

	public function GetGroupList()
	{
		return json_encode($this->groups);
	}
}