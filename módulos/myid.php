<?php

/*
///==[Me Commands]==///
/myid- Returns your info
*/


include __DIR__."/../config/config.php";
include __DIR__."/../config/variables.php";
include_once __DIR__."/../functions/bot.php";
include_once __DIR__."/../functions/db.php";
include_once __DIR__."/../functions/functions.php";

$date1 = date("Y-m-d");
$time = date("h:i:sa");

////////////====[MUTE]====////////////
if(strpos($message, "/myid") === 0 || strpos($message, "!me") === 0){   
    $antispam = antispamCheck($userId);
    addUser($userId);
    
    if($antispam != False){
      bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"[<u>ANTI SPAM</u>] Try again after <b>$antispam</b>s.",
        'parse_mode'=>'html',
        'reply_to_message_id'=> $message_id
      ]);
      return;

    }else{
        $messageidtoedit1 =   bot('sendmessage',[
          'chat_id'=>$chat_id,
          'text'=>"≡- <ins>User ID:</ins> <code>$userId</code>"])
