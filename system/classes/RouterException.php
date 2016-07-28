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
use RuntimeException;

/**
 * RouterException
 */
class RouterException extends RuntimeException
{
	
	/**
	 * Конструктор класса
	 *
	 * @param   mixed   $code
	 *
	 * @access  public
	 * @return  void
	 */
	public function __construct($code = 404)
	{
		parent::__construct('unknown route', $code);
	}
}
