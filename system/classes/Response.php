<?php
/**
 * (c) Fenric Lab, 2010-2016
 * 
 * @product      Fenric Framework
 * @author       Nekhay Anatoly E.
 * @email        support@fenric.ru
 * @site         http://fenric.ru/
 */

namespace Fenric;

/**
 * Import classes
 */
use SimpleXMLElement;

/**
 * Response
 */
class Response extends Object
{
	
	/**
	 * Статус ответа
	 *
	 * @var	    int
	 * @access  protected
	 */
	protected $status = 200;
	
	/**
	 * HTTP заголовки
	 *
	 * @var	    object
	 * @access  protected
	 */
	protected $headers;
	
	/**
	 * HTTP куки
	 *
	 * @var	    object
	 * @access  protected
	 */
	protected $cookies;
	
	/**
	 * Содержимое ответа
	 *
	 * @var	    array
	 * @access  protected
	 */
	protected $content;
	
	/**
	 * Конструктор класса
	 *
	 * @access  public
	 * @return  void
	 */
	public function __construct()
	{
		$this->headers = new Collection();
		
		$this->cookies = new Collection();
	}
	
	/**
	 * Установка статуса ответа
	 *
	 * @param   int      $status
	 *
	 * @access  public
	 * @return  object
	 */
	public function setStatus($status)
	{
		$this->status = $status;
		
		return $this;
	}
	
	/**
	 * Получение статуса ответа
	 *
	 * @access  public
	 * @return  int
	 */
	public function getStatus()
	{
		return $this->status;
	}
	
	/**
	 * Установка HTTP заголовка
	 *
	 * @param   string   $name
	 * @param   string   $value
	 *
	 * @access  public
	 * @return  object
	 */
	public function setHeader($name, $value)
	{
		$this->headers->set($name, $value);
		
		return $this;
	}
	
	/**
	 * Получение HTTP заголовков
	 *
	 * @access  public
	 * @return  object
	 */
	public function getHeaders()
	{
		return $this->headers;
	}
	
	/**
	 * Установка HTTP куки
	 *
	 * @param   string   $name
	 * @param   string   $value
	 * @param   int      $expire
	 * @param   array    $options
	 *
	 * @access  public
	 * @return  object
	 */
	public function setCookie($name, $value, $expire = 0, array $options = [])
	{
		$options += fenric('config::cookies')->all();
		
		$this->cookies->set($name, ['value' => $value, 'expire' => $expire, 'options' => $options]);
		
		return $this;
	}
	
	/**
	 * Получение HTTP кук
	 *
	 * @access  public
	 * @return  object
	 */
	public function getCookies()
	{
		return $this->cookies;
	}
	
	/**
	 * Установка содержимого ответа
	 *
	 * @param   string   $body
	 * @param   string   $type
	 *
	 * @access  public
	 * @return  object
	 */
	public function setContent($body, $type = 'html')
	{
		$this->content = ['body' => $body, 'type' => $type];
		
		return $this;
	}
	
	/**
	 * Получение содержимого ответа
	 *
	 * @access  public
	 * @return  array
	 */
	public function getContent()
	{
		return $this->content;
	}
	
	/**
	 * Сброс свойств класса
	 *
	 * @access  public
	 * @return  object
	 */
	public function reset()
	{
		$this->status = 200;
		
		$this->headers->clear();
		
		$this->cookies->clear();
		
		$this->content = null;
		
		return $this;
	}
	
	/**
	 * Очистка буфера вывода
	 *
	 * @access  public
	 * @return  object
	 */
	public function clean()
	{
		while (ob_get_level() > 0)
		{
			ob_end_clean();
		}
		
		return $this;
	}
	
	/**
	 * Отправка ответа
	 *
	 * @access  public
	 * @return  void
	 */
	public function send()
	{
		$this->trigger('beforeSend');
		
		$this->sendHeaders();
		$this->sendCookies();
		$this->sendContent();
		
		$this->trigger('afterSend');
	}
	
	/**
	 * Отправка HTTP заголовков
	 *
	 * @access  public
	 * @return  void
	 */
	public function sendHeaders()
	{
		http_response_code($this->status);
		
		if ($this->headers->count() > 0)
		{
			$this->trigger('beforeSendHeaders');
			
			foreach ($this->headers->all() as $name => $value)
			{
				header(sprintf('%s: %s', $name, $value), true);
			}
			
			$this->trigger('afterSendHeaders');
		}
	}
	
	/**
	 * Отправка HTTP кук
	 *
	 * @access  public
	 * @return  void
	 */
	public function sendCookies()
	{
		if ($this->cookies->count() > 0)
		{
			$this->trigger('beforeSendCookies');
			
			foreach ($this->cookies->all() as $name => $cookie)
			{
				$options = $cookie['options'] + ['path' => '/', 'domain' => '', 'secure' => false, 'httpOnly' => false];
				
				setcookie($name, $cookie['value'], $cookie['expire'] + time(), $options['path'], $options['domain'], $options['secure'], $options['httpOnly']);
			}
			
			$this->trigger('afterSendCookies');
		}
	}
	
	/**
	 * Отправка контента
	 *
	 * @access  public
	 * @return  void
	 */
	public function sendContent()
	{
		if (isset($this->content))
		{
			switch ($this->content['type'])
			{
				case 'html' :
					header('Content-Type: text/html; charset=UTF-8', true);
					break;
					
				case 'text' :
					header('Content-Type: text/plain; charset=UTF-8', true);
					break;
					
				case 'json' :
					header('Content-Type: application/json; charset=UTF-8', true);
					break;
					
				case 'jsonp' :
					header('Content-Type: application/javascript; charset=UTF-8', true);
					break;
					
				case 'yaml' :
					header('Content-Type: application/x-yaml; charset=UTF-8', true);
					break;
					
				case 'xml' :
					header('Content-Type: application/xml; charset=UTF-8', true);
					break;
			}
			
			$this->trigger('beforeSendContent');
			
			if (is_scalar($this->content['body']))
			{
				echo (string) $this->content['body'];
			}
			
			else if (is_array($this->content['body']))
			{
				echo json_encode($this->content['body']);
			}
			
			else if ($this->content['body'] instanceof View)
			{
				echo $this->content['body']->render();
			}
			
			else if ($this->content['body'] instanceof SimpleXMLElement)
			{
				echo $this->content['body']->asXML();
			}
			
			else if ($this->content['body'] instanceof Closure)
			{
				echo $this->content['body']();
			}
			
			$this->trigger('afterSendContent');
		}
	}
	
	/**
	 * Переадресация
	 *
	 * @param   string   $url
	 * @param   int      $status
	 * @param   call     $callback
	 *
	 * @access  public
	 * @return  void
	 */
	public function redirect($url, $status = 302, callable $callback = null)
	{
		$this->reset()->setStatus($status)->setHeader('Location', $url);
		
		if (is_callable($callback))
		{
			call_user_func($callback, $this);
		}
		
		$this->send();
		
		exit(0);
	}
	
	/**
	 * Переадресация к домашнему адресу
	 *
	 * @param   int      $status
	 * @param   call     $callback
	 *
	 * @access  public
	 * @return  void
	 */
	public function homeward($status = 302, callable $callback = null)
	{
		$request = fenric('request');
		
		$this->redirect($request->getRoot() ?: '/', $status, $callback);
	}
	
	/**
	 * Переадресация к отсылающему адресу
	 *
	 * @param   int      $status
	 * @param   call     $callback
	 *
	 * @access  public
	 * @return  void
	 */
	public function backward($status = 302, callable $callback = null)
	{
		$request = fenric('request');
		
		$referer = $request->environment->get('HTTP_REFERER') ?: $request->getRoot() ?: '/';
		
		$this->redirect($referer, $status, $callback);
	}
}
