<?php

/**
 * Created by PhpStorm.
 * User: gabrycaos
 * Date: 15/01/16
 * Time: 13.11
 */
class PushUtility
{
    private $androidApiKey;
    private $iosProductionEnvironment;
    private $iosPemDebugCertificate;
    private $iosPemProductionCertificate;

    public function getAndroidApiKey()
    {
        return $this->androidApiKey;
    }

    public function getIosPemDebugCertificate()
    {
        return $this->iosPemDebugCertificate;
    }

    public function getIosPemProductionCertificate()
    {
        return $this->iosPemProductionCertificate;
    }

    public function iosProductionEnvironment()
    {
        return $this->iosProductionEnvironment;
    }

    public function setAndroidApiKey($androidApiKey)
    {
        $this->androidApiKey = $androidApiKey;
    }

    public function setIosProductionEnvironment()
    {
        $this->iosProductionEnvironment = true;
    }

    public function setIosDebugEnvironment()
    {
        $this->iosProductionEnvironment = false;
    }

    public function setIosPemDebugCertificate($iosPemDebugCertificate)
    {
        $this->iosPemDebugCertificate = $iosPemDebugCertificate;
    }

    public function setIosPemProductionCertificate($iosPemProductionCertificate)
    {
        $this->iosPemProductionCertificate = $iosPemProductionCertificate;
    }

    public function send($title, $message, $platform, $id_client)
    {
        if ($platform == "ios") {
            $this->ios_push($id_client, $message);
        } else {
            $this->android_push($id_client, $title, $message);
        };
    }

    public function android_push($id_push, $title, $message)
    {
        $apiKey = $this->getAndroidApiKey();
        $registrationIDs = array($id_push);
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $id_push,
            'data' => array("title" => $title, "message" => $message),
        );
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields));

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);
//        echo $result;
    }

    public function ios_push($id_push, $messaggio)
    {
        $badge = 3;
        $sound = 'default';
        $production = $this->iosProductionEnvironment();

        $payload = array();
        $payload['aps'] = array('alert' => $messaggio, 'badge' => intval($badge), 'sound' => $sound);
        $payload = json_encode($payload);

        $apns_url = NULL;
        $apns_cert = NULL;
        $apns_port = 2195;

        if (!$production) {
            $apns_url = 'gateway.sandbox.push.apple.com';
            $apns_cert = $this->getIosPemDebugCertificate();
        } else {
            $apns_url = 'gateway.push.apple.com';
            $apns_cert = $this->getIosPemProductionCertificate();
        }

        $stream_context = stream_context_create();
        stream_context_set_option($stream_context, 'ssl', 'local_cert', $apns_cert);

        $apns = stream_socket_client('ssl://' . $apns_url . ':' . $apns_port, $error, $error_string, 2, STREAM_CLIENT_CONNECT, $stream_context);

        $device_token = $id_push;

        $apns_message = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $device_token)) . chr(0) . chr(strlen($payload)) . $payload;
        fwrite($apns, $apns_message);

//        @socket_close($apns);
//        @fclose($apns);
    }

}