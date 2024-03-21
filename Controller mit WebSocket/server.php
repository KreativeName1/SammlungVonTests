<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require 'vendor/autoload.php';
class WebSocketServer implements Ratchet\MessageComponentInterface
{
    public function onOpen(Ratchet\ConnectionInterface $conn)
    {
      error_log("Neue Verbindung! ({$conn->resourceId})");
    }

    public function onClose(Ratchet\ConnectionInterface $conn)
    {
      error_log("Verbindung {$conn->resourceId} wurde geschlossen");
    }

    public function onError(Ratchet\ConnectionInterface $conn, Exception $e)
    {
      error_log("Ein Fehler ist aufgetreten: {$e->getMessage()}");
      $conn->close();
    }

    public function onMessage(Ratchet\ConnectionInterface $conn, $message)
    {
      $data = json_decode($message);
      if ($data->type == "disconnect") {
        $this->onClose($conn);
        exit();
      }
      else $conn->send($message);

      echo "$message\n";
    }
}

$server = IoServer::factory(
  new HttpServer(
    new WsServer(
      new WebSocketServer()
    )
  ),
  8080
);

echo "Server läuft...\n";
$server->run();
?>