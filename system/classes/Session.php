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
use SessionHandler;
use SessionHandlerInterface;

/**
 * Session
 */
class Session extends Collection
{
	
	/**
	 * Запуск сессии
	 *
	 * @param   object   $handler
	 *
	 * @access  public
	 * @return  void
	 *
	 * @throws  SessionException
	 */
	public function start(SessionHandlerInterface $handler = null)
	{
		if ($this->isReady())
		{
			if (session_set_save_handler($handler ?: new SessionHandler(), false))
			{
				if (session_start())
				{
					$this->trigger('start');
					
					$this->update($_SESSION);
					
					register_shutdown_function([$this, 'close']);
				}
				else throw new SessionException('Failed to start session mechanism.');
			}
			else throw new SessionException('Handler of sessions cannot be set.');
		}
		else throw new SessionException('Mechanism of sessions is running or missing on server.');
	}
	
	/**
	 * Закрытие сессии
	 *
	 * @access  public
	 * @return  void
	 */
	public function close()
	{
		if ($this->isStarted())
		{
			$this->trigger('close');
			
			$_SESSION = $this->all();
			
			session_write_close();
		}
	}
	
	/**
	 * Разрушение сессии
	 *
	 * @access  public
	 * @return  bool
	 */
	public function destroy()
	{
		if ($this->isStarted())
		{
			$this->trigger('destroy');
			
			return session_destroy();
		}
		
		return false;
	}
	
	/**
	 * Перезагрузка сессии
	 *
	 * @access  public
	 * @return  bool
	 */
	public function restart()
	{
		if ($this->isStarted())
		{
			$this->trigger('restart');
			
			return session_regenerate_id(false);
		}
		
		return false;
	}
	
	/**
	 * Проверка готовности механизма сессий к старту
	 *
	 * @access  public
	 * @return  bool
	 */
	public function isReady()
	{
		return (session_status() === PHP_SESSION_NONE);
	}
	
	/**
	 * Проверка активности механизма сессий
	 *
	 * @access  public
	 * @return  bool
	 */
	public function isStarted()
	{
		return (session_status() === PHP_SESSION_ACTIVE);
	}
}
