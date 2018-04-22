<?php
    include 'config.php';
    include 'trie.php';
    //读取过滤词并建立字典树
    Trie($filename);

    //创建websocket服务器对象，监听$url:$port
    $ws = new swoole_websocket_server($wsurl, $port);

    //监听WebSocket连接打开事件
    $ws->on('open', function ($ws, $request) {
        echo 'Handshake success with '.$request->fd.'-----time:'.date("Y-m-d H:i:sa")."\n";
    });

    //监听WebSocket消息事件
    $ws->on('message', function ($ws, $frame) {
        echo "Client-{$frame->fd}'s Message: {$frame->data}\n";
        $data=$frame->data;
        $ary=json_decode($data,true);
        $before=$ary['str'];
        $ary['str']=filter($ary['str']);
        $now=$ary['str'];
        $data=json_encode($ary);
        if ($before==$now) echo "------>未检测出敏感词！\n";
        else echo "------>检测出敏感词！已修改为：".$now."\n";
        $frame->data=$data;
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