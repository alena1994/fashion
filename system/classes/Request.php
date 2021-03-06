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
 * Request
 */
class Request extends Object
{
	
	/**
	 * {description}
	 *
	 * @var     object
	 * @access  public
	 */
	public $query;
	
	/**
	 * {description}
	 *
	 * @var     object
	 * @access  public
	 */
	public $post;
	
	/**
	 * {description}
	 *
	 * @var     object
	 * @access  public
	 */
	public $files;
	
	/**
	 * {description}
	 *
	 * @var     object
	 * @access  public
	 */
	public $cookies;
	
	/**
	 * {description}
	 *
	 * @var     object
	 * @access  public
	 */
	public $environment;
	
	/**
	 * {description}
	 *
	 * @var     string
	 * @access  public
	 */
	public $rawBody;
	
	/**
	 * Конструктор класса
	 *
	 * @access  public
	 * @return  void
	 */
	public function __construct()
	{
		// @see http://php.net/manual/reserved.variables.get.php
		$this->query = new Collection($_GET);
		
		// @see http://php.net/manual/reserved.variables.post.php
		$this->post = new Collection($_POST);
		
		// @see http://php.net/manual/reserved.variables.files.php
		$this->files = new Collection($_FILES);
		
		// @see http://php.net/manual/reserved.variables.cookies.php
		$this->cookies = new Collection($_COOKIE);
		
		// @see http://php.net/manual/reserved.variables.environment.php
		// @see http://php.net/manual/reserved.variables.server.php
		$this->environment = new Collection($_ENV + $_SERVER);
		
		// @see http://php.net/manual/wrappers.php.php
		$this->rawBody = file_get_contents('php://input');
	}
	
	/**
	 * Получение родительской директории
	 *
	 * @access  public
	 * @return  string
	 */
	public function getRoot()
	{
		$script = $_SERVER['SCRIPT_NAME'];
		
		$dirname = pathinfo($script, PATHINFO_DIRNAME);
		
		return rtrim($dirname, DIRECTORY_SEPARATOR);
	}
	
	/**
	 * Получение HTTP метода
	 *
	 * @access  public
	 * @return  string
	 */
	public function getMethod()
	{
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}
	
	/**
	 * Получение запрошенного хоста
	 *
	 * @access  public
	 * @return  string
	 */
	public function getHost()
	{
		return parse_url('scheme://' . $_SERVER['HTTP_HOST'], PHP_URL_HOST);
	}
	
	/**
	 * Получение запрошенного порта
	 *
	 * @access  public
	 * @return  int
	 */
	public function getPort()
	{
		return parse_url('scheme://' . $_SERVER['HTTP_HOST'], PHP_URL_PORT);
	}
	
	/**
	 * Получение запрошенного URI
	 *
	 * @access  public
	 * @return  string
	 */
	public function getURI()
	{
		return urldecode($_SERVER['REQUEST_URI']);
	}
	
	/**
	 * Получение запрошенного пути
	 *
	 * @access  public
	 * @return  string
	 */
	public function getPath()
	{
		return parse_url($this->getURI(), PHP_URL_PATH);
	}
	
	/**
	 * Получение запрошенных параметров
	 *
	 * @access  public
	 * @return  string
	 */
	public function getQuery()
	{
		return parse_url($this->getURI(), PHP_URL_QUERY);
	}
	
	/**
	 * Это OPTIONS запрос
	 *
	 * @access  public
	 * @return  bool
	 */
	public function isOptions()
	{
		return strcmp($this->getMethod(), 'OPTIONS') === 0;
	}
	
	/**
	 * Это HEAD запрос
	 *
	 * @access  public
	 * @return  bool
	 */
	public function isHead()
	{
		return strcmp($this->getMethod(), 'HEAD') === 0;
	}
	
	/**
	 * Это GET запрос
	 *
	 * @access  public
	 * @return  bool
	 */
	public function isGet()
	{
		return strcmp($this->getMethod(), 'GET') === 0;
	}
	
	/**
	 * Это POST запрос
	 *
	 * @access  public
	 * @return  bool
	 */
	public function isPost()
	{
		return strcmp($this->getMethod(), 'POST') === 0;
	}
	
	/**
	 * Это PUT запрос
	 *
	 * @access  public
	 * @return  bool
	 */
	public function isPut()
	{
		return strcmp($this->getMethod(), 'PUT') === 0;
	}
	
	/**
	 * Это PATCH запрос
	 *
	 * @access  public
	 * @return  bool
	 */
	public function isPatch()
	{
		return strcmp($this->getMethod(), 'PATCH') === 0;
	}
	
	/**
	 * Это DELETE запрос
	 *
	 * @access  public
	 * @return  bool
	 */
	public function isDelete()
	{
		return strcmp($this->getMethod(), 'DELETE') === 0;
	}
	
	/**
	 * Это AJAX запрос
	 *
	 * @access  public
	 * @return  bool
	 */
	public function isAjax()
	{
		return strcmp($this->environment->get('HTTP_X_REQUESTED_WITH'), 'XMLHttpRequest') === 0;
	}
}
