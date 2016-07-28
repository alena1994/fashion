<?php 
namespace Fenric\Controllers;
use Fenric\Controller;
use Fenric\View;

class Index extends Controller
{
	public function render()
	{
		$this->response->setContent(new View('/main'));
		$this->response->send();
	}
}