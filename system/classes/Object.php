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
use Closure;

/**
 * Object
 */
abstract class Object
{
	
	/**
	 * Слушатели событий класса
	 *
	 * @var     array
	 * @access  private
	 */
	private $_eventsListeners = [];
	
	/**
	 * Регистрация слушателя события класса
	 *
	 * @param   string   $event
	 * @param   call     $listener
	 *
	 * @access  public
	 * @return  void
	 */
	final public function on($event, callable $listener)
	{
		$this->_eventsListeners[$event][] = $listener;
	}
	
	/**
	 * Удаление слушателей события класса
	 *
	 * @param   string   $event
	 *
	 * @access  public
	 * @return  void
	 */
	final public function off($event)
	{
		$this->_eventsListeners[$event] = null;
	}
	
	/**
	 * Вызов слушателей события класса
	 *
	 * @param   string   $event
	 * @param   array    $args
	 *
	 * @access  public
	 * @return  bool
	 */
	final public function trigger($event, $args = null)
	{
		// Если слушателю события необходимо передать
		// всего один единственный параметр это можно
		// сделать напрямую кроме случаев с массивами
		$args = (array) $args;
		
		// Первым аргументом слушатель события всегда
		// будет получать экземпляр настоящего класса
		array_unshift($args, $this);
		
		if (isset($this->_eventsListeners[$event]))
		{
			foreach ($this->_eventsListeners[$event] as $listener)
			{
				if (false === call_user_func_array($listener, $args))
				{
					return false;
				}
			}
		}
		
		return true;
	}
	
	/**
	 * Получение имени класса
	 *
	 * @access  public
	 * @return  string
	 */
	final public function getClass()
	{
		return get_class($this);
	}
	
	/**
	 * Проверка существования константы класса
	 *
	 * @param   string  $constant
	 *
	 * @access  public
	 * @return  bool
	 */
	final public function hasConstant($constant)
	{
		return defined($this->getClass() . '::' . $constant);
	}
	
	/**
	 * Проверка существования свойства класса
	 *
	 * @param   string  $property
	 *
	 * @access  public
	 * @return  bool
	 */
	final public function hasProperty($property)
	{
		return property_exists($this, $property);
	}
	
	/**
	 * Проверка существования метода класса
	 *
	 * @param   string  $method
	 *
	 * @access  public
	 * @return  bool
	 */
	final public function hasMethod($method)
	{
		return method_exists($this, $method);
	}
	
	/**
	 * Проверка существования сеттера для свойства класса
	 *
	 * @param   string  $property
	 * @param   string  $setter
	 *
	 * @access  public
	 * @return  bool
	 */
	final public function hasSetterProperty($property, & $setter)
	{
		$setter = 'set' . ucfirst($property);
		
		return $this->hasProperty($property) && $this->hasMethod($setter);
	}
	
	/**
	 * Проверка существования геттера для свойства класса
	 *
	 * @param   string  $property
	 * @param   string  $getter
	 *
	 * @access  public
	 * @return  bool
	 */
	final public function hasGetterProperty($property, & $getter)
	{
		$getter = 'get' . ucfirst($property);
		
		return $this->hasProperty($property) && $this->hasMethod($getter);
	}
	
	/**
	 * Замыкающееся связывание с экземпляром класса через анонимную функцию
	 *
	 * @param   call    $closure
	 * @param   array   $arguments
	 *
	 * @access  public
	 * @return  object
	 */
	final public function bind(Closure $closure, array $arguments = [])
	{
		$fn = $closure->bindTo($this, $this->getClass());
		
		call_user_func_array($fn, $arguments);
		
		return $this;
	}
}
