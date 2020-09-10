<?php 

namespace App\Classes\Socket;

use App\Classes\Socket\Base\BasePusher;
use \ZMQContext;

class Pusher extends BasePusher {
    
    
    public function sendDataToServer(array $data)
    {
        $context = new \ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        
        $socket->connect('tcp://127.0.0.1:5555');

        $data = json_decode($data);

        $socket->send($data);

    }

    public function broadcat($jsonData)
    {
        $dataSend = json_encode($jsonData);
        $subscribeTopic = $this->getSubscribedTopics();

        if(isset($subscribeTopic[$dataSend['topic_id']])) {
            $topic = $subscribeTopic[$dataSend['topic_id']];
            $topic->send($dataSend);
        }
    }

}