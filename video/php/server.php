<?php
    include 'config.php';

    //创建websocket服务器对象，监听$url:$port
    $ws = new swoole_websocket_server($wsurl, $port);

    //监听WebSocket连接打开事件
    $ws->on('open', function ($ws, $request) {
        echo 'handshake success with '.$request->fd.'-----time:'.date("Y-m-d H:i:sa")."\n";
    });

    //监听WebSocket消息事件
    $ws->on('message', function ($ws, $frame) {
        echo "client-{$frame->fd}'s Message: {$frame->data}\n";
        foreach($ws->connections as $fd){
            $ws->push($fd,"{$frame->data}");
        }

    });

    //监听WebSocket连接关闭事件
    $ws->on('close', function ($ws, $fd) {
        echo "client-{$fd} is closed\n";
    });

    $ws->start();
?>