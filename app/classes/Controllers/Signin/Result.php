<?php 
namespace Fenric\Controllers\Signin;
use Fenric\Controller;
use Fenric\View;

class Result extends Controller
{
	public function render()
	{
		
		$this->response->setContent(new View('signin/result'));
		$this->response->send();

	}
}