<?php
/**
 * (c) Fenric Lab, 2010-2016
 * 
 * @product      Fenric Framework
 * @author       Nekhay Anatoly E.
 * @email        support@fenric.ru
 * @site         http://fenric.ru/
 */

namespace Fenric\DB;

/**
 * Expression
 */
class Expression
{
	
	/**
	 * Свободное выражение
	 *
	 * @var     string
	 * @access  protected
	 */
	protected $expression;
	
	/**
	 * Инициализация свободного выражения
	 *
	 * @param   string  $expression
	 *
	 * @access  public
	 * @return  void
	 */
	public function __construct($expression)
	{
		$this->expression = $expression;
	}
	
	/**
	 * Получение свободного выражения
	 *
	 * @access  public
	 * @return  string
	 */
	public function __toString()
	{
		return $this->expression;
	}
}
