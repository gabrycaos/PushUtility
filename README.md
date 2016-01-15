##PushUtility php library

PushUtility is a small library (CURRENTLY ALPHA RELEASE) for sending push notifications to iOS (trough Apple Push Notification Service) and Android (trough Google Cloud Messaging) device through php.

###How to use

Simply put PushUtility.php file in your project directory and call it with require from your project script;

after this you have to create an instance and do a little configuration of the object; here is an example:

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
        
and finally, after this configuration, you can send a push notification with:

        //platform can be 'ios' or 'android', all lowercase, id_client is the registration id of the client that receive the notification
        $pushUtility->send($title, $message, $platform, $id_client);
        
        
you can find how to generate ios pem certificates [here](http://www.raywenderlich.com/32960/apple-push-notification-services-in-ios-6-tutorial-part-1)
    in my case it doesn't works, i have to modify the command
        
         $ openssl pkcs12 -nocerts -out PushChatKey.pem -in PushChatKey.p12
         
with:
            
            $ openssl pkcs12 -nocerts -out PushChatKey.pem -in PushChatKey.p12 -nodes -nocerts
         
and it works!

###helpful links

[GCM documentation](https://developers.google.com/cloud-messaging/)
[APNs documentation](https://developer.apple.com/library/ios/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Chapters/ApplePushService.html)
