<?php 
namespace Fenric\Controllers\CPanel;
use Fenric\Controller;
use Fenric\View;

class Index extends Controller
{
	public function render()
	{
		
		$this->response->setContent(new View('cpanel/index'));
		$this->response->send();

	}
}