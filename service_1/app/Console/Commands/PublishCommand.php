<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle(): void
    {
        $connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.username'),
            config('rabbitmq.password')
        );

        $channel = $connection->channel();

//        $channel->exchange_declare('laravel', 'fanout', false, true, false);
//        $channel->queue_declare('laravel', false, true, false, false);
//
//        $channel->queue_bind('laravel', 'laravel');

        $data = [
            'title' => 'Some title',
            'content' => 'Some content',
        ];

        $msg = new AMQPMessage(json_encode($data, JSON_THROW_ON_ERROR));
        $channel->basic_publish($msg, 'laravel',);

        echo " [x] Sent DATA\n";

        $channel->close();
        $connection->close();
    }
}
