<?php

/*

///==[Bin Lookup Commands]==///

/bin 123 - Returns the Bin info

*/


include __DIR__."/../config/config.php";
include __DIR__."/../config/variables.php";
include_once __DIR__."/../functions/bot.php";
include_once __DIR__."/../functions/db.php";
include_once __DIR__."/../functions/functions.php";


////////////====[MUTE]====////////////
if(strpos($message, "/Bin ") === 0 || strpos($message, "!Bin ") === 0){   
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
        $messageidtoedit1 = bot('sendmessage',[
          'chat_id'=>$chat_id,
          'text'=>"<b>Wait for Result...</b>",
          'parse_mode'=>'html',
          'reply_to_message_id'=> $message_id

        ]);

        $messageidtoedit = capture(json_encode($messageidtoedit1), '"message_id":', ',');
        $bin = substr($message, 5);
        $bin = substr($bin, 0, 6);
        
        if(preg_match_all("/^\d{6,20}$/", $bin, $matches)) {
            $bin = $matches[0][0];
        

            ###CHECKER PART###  
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://binsu-api.vercel.app/api/'.$bin.'');
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Host: binsu-api.vercel.app/api/',
            'Cookie: _ga=GA1.2.549903363.1545240628; _gid=GA1.2.82939664.1545240628',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8'));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
            $result = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($result, true);
            $bank = $data['data']['bank'];
            $bin = $data['data']['bin'];
             $country = $data['data']['country'];
             $brand = $data['data']['vendor'];
             $level = $data['data']['level'];
             $type = $data['data']['type'];
             $dial = $data['data']['dialCode'];
             $flag = $data['data']['countryInfo']['emoji'];
             $result1 = $data['result'];
            
            /////////////////////==========[Unavailable if empty]==========////////////////
            
            
            if (empty($bank)) {
            	$bank = "Unavailable";
            }
            if (empty($country)) {
            	$country = "Unavailable";
            }
            if (empty($brand)) {
            	$brand = "Unavailable";
            }
            if (empty($type)) {
            	$type = "Unavailable";
            }
            if (empty($level)) {
            	$level = "Unavailable";
            }
            if (empty($flag)) {
            	$flag = "Unavailable";
            }

            ###END OF CHECKER PART###
            
            if(strlen($bin) < '6'){ 
              bot('editMessageText',[
                'chat_id'=>$chat_id,
                'message_id'=>$messageidtoedit,
                'text'=>"<b>❌ INVALID BIN LENGTH ❌</b>
<b>Checked By <a href='tg://user?id=$userId'>$firstname</a></b>",
                'parse_mode'=>'html',
                'reply_to_message_id'=> $message_id]);}
            

            elseif($fim){ //If Response from Bin Lookup Site exists
                bot('editMessageText',[
              'chat_id'=>$chat_id,
              'message_id'=>$messageidtoedit,
              'text'=>" <b>↱VALID BIN ✅!
↳Bin: <code>$bin</code> 
↳Brand: <ins>$brand</ins>
↳Type: <ins>$type</ins>
↳Bank: <ins>$bank</ins>
↳Country: <ins>$country</ins>
━━━━━━━━━━━━━━━━━━━━━━━
Checked By <a href='tg://user?id=$userId'>$firstname</a>
Bot By: <a href='t.me/yhvga'>yhvga</a> ↳•↲ </b> ",
              'parse_mode'=>'html',
              'reply_to_message_id'=> $message_id,
              'disable_web_page_preview'=>'true']);}
            
            else{
              bot('editMessageText',[
                'chat_id'=>$chat_id,
                'message_id'=>$messageidtoedit,
                'text'=>"<b>❌ INVALID BIN ❌</b>
<b>Checked By <a href='tg://user?id=$userId'>$firstname</a></b>",
                'parse_mode'=>'html',
                'disable_web_page_preview'=>'true'
                
            ]);}
          
        }else{
          bot('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$messageidtoedit,
            'text'=>"<b>Never Gonna Give you Up!

Provide a Bin!</b>",
            'parse_mode'=>'html',
            'disable_web_page_preview'=>'true'
            
        ]);

        }
    }
}


?>
