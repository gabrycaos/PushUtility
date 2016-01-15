<?php
/**
 * Created by PhpStorm.
 * User: gabrycaos
 * Date: 15/01/16
 * Time: 13.49
 */
require 'PushUtility.php';

$pushUtility = new PushUtility();

//$apiKey is a string that contains your GCM api key for sending notification on android
$pushUtility->setAndroidApiKey($apiKey);

//this command is for set ios debug environment on APNs for iOS,
$pushUtility->setIosDebugEnvironment();

//if you are working in production environment, you have to use following command
$pushUtility->setIosProductionEnvironment();

//for iOS you have to load the APNs pem certificates on the server, and set this certificates on PushUtility with this commands
$pushUtility->setIosPemDebugCertificate($iosPemDebugCertificatePath);
//or, in production
$pushUtility->setIosPemProductionCertificate($iosPemProductionCertificatePath);


$pushUtility->send($title, $message, $platform, $id_client);
//platform can be 'ios' or 'anroid', without quotes and all lowercase, id_client is the registration id of the client that receive the notification