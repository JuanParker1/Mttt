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
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://bin-check-dr4g.herokuapp.com/api/'.$bin.'');
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Host: bin-check-dr4g.herokuapp.com/api/',
            'Cookie: _ga=GA1.2.549903363.1545240628; _gid=GA1.2.82939664.1545240628',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8'));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
            $fim = curl_exec($ch);
            $da = capture($fim, '"data":{"name":"', '"');
            $bi = capture($fim, '"bin":"', '"');
            $ve = capture($fim, '"vendor":"', '"');
            $ty = capture($fim, '"type":{"name":"', '"');
            $le = capture($fim, '"level":"', '"');
            $ban = capture($fim, '"bank":"', '"');
            $co = capture($fim, '"country":"', '"');
            $cc = capture($fim, '"countryInfo":"', '"');
            $nm = capture($fim, '"name":"', '"');
            $em = capture($fim, '"emoji":"', '"');
            $co = capture($fim, '"code":"', '"');      
            $dial = capture($fim, '"dialCode":"', '"');      

            /////////////////////==========[Unavailable if empty]==========////////////////
            
            
            if (empty($schemename)) {
            	$schemename = "Unavailable";
            }
            if (empty($typename)) {
            	$typename = "Unavailable";
            }
            if (empty($brand)) {
            	$brand = "Unavailable";
            }
            if (empty($bank)) {
            	$bank = "Unavailable";
            }
            if (empty($bname)) {
            	$bname = "Unavailable";
            }
            if (empty($phone)) {
            	$phone = "Unavailable";
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
              'text'=>"BIN: <code>$bi</code> $emoji
Card Brand: <b><ins>$ve</ins></b>
Card Type: <b><ins>$ty</ins></b>
Card Level: <b><ins>$le</ins></b>
Bank Name: <b><ins>$ban</ins></b> $emoji
Country: <b><ins>$co</ins> - 💲<ins>$currency</ins></b>
Contact: <b><ins>$dial</ins></b>
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
