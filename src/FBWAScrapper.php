<?php
namespace FBWAScrapper;

use FBWAScrapper\Login\Login;
use FBWAScrapper\Logout\Logout;
use FBWAScrapper\Groups\Group;
use FBWAScrapper\Phones\Phone;

class FBWAScrapper
{
	private $config;

	public function __construct($email, $pass, $socks = '')
	{
		$this->config = array('email' => $email, 'pass' => $pass, 'socks' => $socks);
	}

	public function Login()
	{
		$login = new Login($this->config['email'], $this->config['pass'], $this->config['socks']);
		return json_decode($login->Login());
	}

	public function GetGroupList()
	{
		$group = new Group($this->config['socks']);
		$group->ParseGroupList();
		return json_decode($group->GetGroupList());
	}

	public function GetPhones()
	{
		$phones = new Phone($this->config['socks']);
		$phones->Phones();
		return json_decode($phones->GetPhones());
	}

	public function Logout()
	{
		$logout = new Logout($this->config['socks']);
		return json_decode($logout->Logout());
	}
}