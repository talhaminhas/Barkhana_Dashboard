<?php
require 'C:\xampp\htdocs\barkhana\vendor\autoload.php';
require 'C:\xampp\htdocs\barkhana\application\core\BE_Controller.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

//$cond['user_id'] = "usr7b57a7e8bdbe69ba20b12d33650aec06";
  //      $user_lat = $this->User->get_one_by($conds)->user_lat;
    //echo $user_lat;
    echo'server has started';
class Chat extends BE_Controller implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {

        $cond['user_id'] = "usr7b57a7e8bdbe69ba20b12d33650aec06";
        //$user_lat = $this->User->get_one_by($conds)->user_lat;
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $from->send('return from server'.$msg);
        
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

$server = IoServer::factory(
    //new HttpServer(
        //new WsServer(
            new Chat(),
        //)
    //),
    8085
);

$server->run();


?>