<?php 
namespace Fenric\Controllers\Sitemap;
use Fenric\Controller;
use Fenric\View;

class Index extends Controller
{
	public function render()
	{
		$this->response->setContent(new View('sitemap/index'));
		$this->response->send();
	}
}