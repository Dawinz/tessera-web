<?php
require  __DIR__ . '/vendor/autoload.php';

use Pimple\Container;
use Mulopwe\Orange\SMS\OnlineSms;
use Mulopwe\Orange\SMS\Http\SMSClient;



$container = new Container();

$container['clientID'] = 'AsHA9HTcLQBRFAZmoBpwdAwkSlzoYuW3';
$container['clientSecret'] = 'eiVH6qkPf296gCcZ';

$smsClient = new SMSClient($container['clientID'], $container['clientSecret'], $container);

$onlineSms = new OnlineSms($smsClient);

//send sms
$onlineSms->from('243897701399') //sender number
          ->to('243835426545','243894101324','243899826091') //recipient number
          ->message('Message de teste pour Hikman Mohamed') //message
          ->send();


//get your balance
//$response = $onlineSms->balance('COD');

//order history
//$response = $onlineSms->ordersHistory('COD');

//get statistics
//$response = $onlineSms->statistics('COD',);




