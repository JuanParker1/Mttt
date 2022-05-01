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
if(strpos($message, "/bin ") === 0 || strpos($message, "!bin ") === 0){   
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
    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => "https://binsu-api.vercel.app/api/".$bin,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
    "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
    "accept-language: en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7",
    "sec-fetch-dest: document",
    "sec-fetch-site: none",
    "user-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1"
   ],
   ]);

 $result = curl_exec($curl);
 curl_close($curl);
 $data = json_decode($result, true);
 $bank = $data['data']['bank'];
 $country = $data['data']['country'];
 $brand = $data['data']['vendor'];
 $level = $data['data']['level'];
 $type = $data['data']['type'];
$flag = $data['data']['countryInfo']['emoji'];

            /////////////////////==========[Unavailable if empty]==========////////////////
            
            
            if (empty($country)) {
            	$country = "Unavailable";
            }
            if (empty($brand)) {
            	$brand= "Unavailable";
            }
            if (empty($level)) {
            	$level = "Unavailable";
            }
            if (empty($bank)) {
            	$bank = "Unavailable";
            }
            if (empty($flag)) {
            	$flg = "Unavailable";
            }
            if (empty($type)) {
            	$type = "Unavailable";
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
              'text'=>"BIN: <code>$bin</code> $emoji
Card Brand: <b><ins>$ve</ins></b>
Card Type: <b><ins>$type</ins></b>
Card Level: <b><ins>$level</ins></b>
Bank Name: <b><ins>$ban</ins></b> 
Country: <b><ins>$country $flag</ins>
[🔥SUSSEFULLY🔥]
<b>━━━━━━━━━━━━━
Checked By <a href='tg://user?id=$userId'>$firstname</a></b>
<b>Bot By: <a href='t.me/yhvga'>yhvga</a></b>",
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
