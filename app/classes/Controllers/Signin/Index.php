<?php 
namespace Fenric\Controllers\Signin;
use Fenric\Controller;
use Fenric\View;

class Index extends Controller
{
	public function render()
	{
		
		

		$store['admin'] = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
		$store['tester'] = password_hash('tester123', PASSWORD_BCRYPT, ['cost' => 12]);
		$store['fucker1933'] = password_hash('fucker1933123', PASSWORD_BCRYPT, ['cost' => 12]);
		$error = null;
	
		if ($this->request->isPost())
		{
			if (ctype_alnum($this->request->post->get('username')))
			{
				if (strlen($this->request->post->get('username')) >= 3)
  				{
  					if (strlen($this->request->post->get('username')) <= 32)
   					{
   						if (isset($store[$this->request->post->get('username')]))
   						{
   							if (strlen($this->request->post->get('password')) >= 4)
   							{
   								if (strlen($this->request->post->get('password')) <= 256)
   								{
   								 	if (password_verify($this->request->post->get('password'), $store[$this->request->post->get('username')]))
   								 	{
                      fenric('session')->set('username', $this->request->post->get('username'));
   								 		$this->response->redirect('/result/');
       								}
       								else $error = 'Неправильный пароль';
      							}
      							else $error = 'Слишком длинный пароль';
     						}
     						else $error = 'Слишком короткий пароль';
    					}
    					else $error = 'Учётная запись не найдена';
   					}
   					else $error = 'Слишком длинное имя пользователя';
  				}
  				else $error = 'Слишком короткое имя пользователя';
 			}
 			else $error = 'Некорректное имя пользователя';
		}
		$view = new View('signin/sign-in', ['error' => $error]);
		$this->response->setContent($view)->send();

	}
}