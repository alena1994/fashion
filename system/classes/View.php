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
 * View
 */
class View extends Object
{
	
	/**
	 * Имя представления
	 *
	 * @var     string
	 * @access  protected
	 */
	protected $name;
	
	/**
	 * Данные представления
	 *
	 * @var     array
	 * @access  protected
	 */
	protected $data;
	
	/**
	 * Макет представления
	 *
	 * @var     object
	 * @access  protected
	 */
	protected $layout;
	
	/**
	 * Секции представления
	 *
	 * @var     array
	 * @access  protected
	 */
	protected $sections = [];
	
	/**
	 * Инициализация представления
	 *
	 * @param   string   $name
	 * @param   array    $data
	 *
	 * @access  public
	 * @return  void
	 */
	public function __construct($name, array $data = [])
	{
		$this->name = $name;
		
		$this->data = $data;
	}
	
	/**
	 * Получение адреса представления
	 *
	 * @access  public
	 * @return  string
	 */
	public function getPath()
	{
		return fenric()->path('views', $this->name . '.phtml');
	}
	
	/**
	 * Рендеринг представления
	 *
	 * @access  public
	 * @return  string
	 */
	public function render()
	{
		if (file_exists($this->getPath()))
		{
			if (ob_start())
			{
				extract($this->data);
				
				include $this->getPath();
				
				$content = ob_get_clean();
				
				if ($this->layout instanceof View)
				{
					$this->layout->sections += $this->sections;
					
					$this->layout->sections += ['content' => $content];
					
					$content = $this->layout->render();
				}
				
				return ltrim($content, PHP_EOL);
			}
		}
	}
	
	/**
	 * Получение экземпляра нового представления
	 *
	 * @param   string   $name
	 * @param   array    $data
	 *
	 * @access  protected
	 * @return  object
	 */
	protected function make($name, array $data = [])
	{
		return new static($name, $data + $this->data);
	}
	
	/**
	 * Получение отрендеренного нового представления
	 *
	 * @param   string   $name
	 * @param   array    $data
	 *
	 * @access  protected
	 * @return  string
	 */
	protected function fetch($name, array $data = [])
	{
		return $this->make($name, $data)->render();
	}
	
	/**
	 * Отображение отрендеренного нового представления
	 *
	 * @param   string   $name
	 * @param   array    $data
	 *
	 * @access  protected
	 * @return  void
	 */
	protected function insert($name, array $data = [])
	{
		echo $this->make($name, $data)->render();
	}
	
	/**
	 * Наследования макета представления
	 *
	 * @param   string   $name
	 * @param   array    $data
	 *
	 * @access  protected
	 * @return  void
	 */
	protected function layout($name, array $data = [])
	{
		$this->layout = $this->make($name, $data);
	}
	
	/**
	 * Получение содержимого секции представления
	 *
	 * @param   string   $section
	 * @param   string   $default
	 *
	 * @access  protected
	 * @return  string
	 */
	protected function section($section, $default = null)
	{
		if (isset($this->sections[$section]))
		{
			return $this->sections[$section];
		}
		
		return $default;
	}
	
	/**
	 * Начало записи содержимого секции представления
	 *
	 * @param   string   $section
	 *
	 * @access  protected
	 * @return  void
	 */
	protected function start($section)
	{
		if (ob_start())
		{
			$this->sections[] = $section;
		}
	}
	
	/**
	 * Конец записи содержимого секции представления
	 *
	 * @access  protected
	 * @return  void
	 */
	protected function end()
	{
		if (count($this->sections) > 0)
		{
			$section = array_pop($this->sections);
			
			$this->sections[$section] = ob_get_clean();
		}
	}
	
	/**
	 * Экранирование данных
	 *
	 * @param   string   $value
	 *
	 * @access  protected
	 * @return  string
	 */
	protected function e($value)
	{
		return htmlentities($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
	}
}
