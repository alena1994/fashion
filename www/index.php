<?php
//exit((string) PHP_VERSION);
// Подключение классов
use Fenric\Application;

// Отключение вывода ошибок
ini_set('display_errors', true);

// Установка уровня протоколирования ошибок
error_reporting(E_ALL);

// Проверка минимальной версии PHP требуемой фреймворком
version_compare(PHP_VERSION, '5.6', '>=') or die('Requires version the PHP >= 5.6');

// Подключение фреймворка
require_once '../system/fenric.php';

// Инициализация фреймворка
fenric()->init(array('env' => 'development', 'strict' => true));

fenric()->registerDisposableSharedService('session', function()
{
 return new Fenric\Session();
});

// Инициализация маршрутизатора
//new объявление класса, router  инициализация переменной
$router = new Fenric\Router(fenric('request'), fenric('response'));

// Главная страница сайта
// Маршрут доступен только по HTTP GET методу
$router->get('/', 'Fenric\\Controllers\\Index');


// Страница блога
// Маршрут доступен только по HTTP GET методу
$router->get('/aboutus/', 'Fenric\\Controllers\\Aboutus\\Index');

// Просмотр поста
// Маршрут доступен только по HTTP GET методу
$router->get('/articles/', 'Fenric\\Controllers\\Articles\\Index');

$router->get('/contactus/', 'Fenric\\Controllers\\Contactus\\Index');

$router->get('/sitemap/', 'Fenric\\Controllers\\Sitemap\\Index');

$router->safe('/sign-in/', 'Fenric\\Controllers\\Signin\\Index');

$router->get('/result/', 'Fenric\\Controllers\\Signin\\Result');

$router->get('/cpanel/', 'Fenric\\Controllers\\Cpanel\\Index');

//запуск сессии
fenric('session')->start();
// Запуск маршрутизатора
$router->run();

// Успешное завершение работы приложения
exit(0);

