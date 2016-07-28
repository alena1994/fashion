<?php 
namespace Fenric\Controllers\Articles;
use Fenric\Controller;
use Fenric\View;

class Index extends Controller
{
	public function render()
	{
		$this->response->setContent(new View('articles/index'));
		$this->response->send();
	}
}