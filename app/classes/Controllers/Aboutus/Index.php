<?php 
namespace Fenric\Controllers\Aboutus;
use Fenric\Controller;
use Fenric\View;

class Index extends Controller
{
	public function render()
	{
		$this->response->setContent(new View('aboutus/index'));
		$this->response->send();
	}
}