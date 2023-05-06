<?php

namespace console\controllers;

use yii\console\Controller;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii;

class RabbitmqController extends Controller {

    public function actionListen() {
        $mqConfig = Yii::$app->params['rabbitmq_config'];
        $connection = new AMQPConnection($mqConfig['host'], $mqConfig['port'], $mqConfig['user'], $mqConfig['password']);
        $channel = $connection->channel();

        $channel->queue_declare($mqConfig['feedback_queue_name'], false, false, false, false);

        echo ' * Waiting for messages. To exit press CTRL+C', "\n";

        $callback = function($msg){

            echo " * Message received", "\n";
            $data = $msg->body;

            echo " * Message: ". $data, "\n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($mqConfig['feedback_queue_name'], '', false, false, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }
    }

}
