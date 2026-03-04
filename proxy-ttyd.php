#!/usr/local/bin/php
<?php
require __DIR__ . '/vendor/autoload.php';

use React\Socket\SocketServer;
use React\Socket\Connector;
use React\Socket\ConnectionInterface;

$unixSocketPath = '/var/run/ttyd.sock';
$localListenPort = '0.0.0.0:8080';

$loop = React\EventLoop\Loop::get();
$server = new SocketServer($localListenPort, [], $loop);
$connector = new Connector($loop);

echo "Proxy запущен на $localListenPort\n";
echo "Трансляция в сокет: $unixSocketPath\n";

$server->on('connection', function (ConnectionInterface $browserConn) use ($connector, $unixSocketPath) {
    echo "Log: Новое соединение из браузера\n";

    // Подключаемся к ttyd через Unix-сокет
    $connector->connect("unix://$unixSocketPath")->then(
        function (ConnectionInterface $ttydConn) use ($browserConn) {
            echo "Log: Соединение с ttyd установлено, запускаю pipe\n";
            
            // Соединяем потоки напрямую
            $browserConn->pipe($ttydConn);
            $ttydConn->pipe($browserConn);

            // Обработка закрытия
            $ttydConn->on('close', function() use ($browserConn) { $browserConn->close(); });
            $browserConn->on('close', function() use ($ttydConn) { $ttydConn->close(); });
        },
        function (Exception $e) use ($browserConn) {
            echo "Error: Не удалось подключиться к ttyd сокету: " . $e->getMessage() . "\n";
            $browserConn->end("HTTP/1.1 502 Bad Gateway\r\n\r\n");
        }
    );
});

$loop->run();
