<?php 
namespace Fenric\Controllers\Contactus;
use Fenric\Controller;
use Fenric\View;

class Index extends Controller
{
	public function render()
	{
		$this->response->setContent(new View('contactus/index'));
		$this->response->send();
	}
}