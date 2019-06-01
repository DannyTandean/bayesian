<?php

require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\Message;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\MessageToRegistrationToken;
/**
 *
 */
class Db_firebase
{
  public function pushNotif($token,$title,$body,$kode)
  {
    $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/vendor/poihrd-418d3-ee60601e5cad.json');
    $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->create();
    $messaging = $firebase->getMessaging();

    $notification = Notification::fromArray([
        'title' => $title,
        'body' => $body
    ]);

    $data = [
      'kode' => $kode,
      'title' => $title,
      'content' => $body,
      'sound' => 'quite_impressed.mp3',
      'port' => "4000"
    ];

    $message = CloudMessage::withTarget('token', $token)
          // ->withNotification($notification) // optional
          ->withData($data);
    // $message->withNotification($notification);
    $messages = $messaging->send($message);

    return $messages;
  }

  public function sendNotifTopic($topic,$title,$body,$kode)
  {
    $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/vendor/poihrd-418d3-ee60601e5cad.json');
    $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->create();
    $messaging = $firebase->getMessaging();

    $notification = Notification::fromArray([
        'title' => $title,
        'body' => $body
    ]);

    $data = [
      'kode' => $kode,
      'title' => $title,
      'content' => $body,
      'sound' => 'quite_impressed.mp3',
      'port' => "4000"
    ];

    $message = CloudMessage::withTarget('topic', $topic)
    // ->withNotification($notification) // optional
    ->withData($data)
    ;

    $messages = $messaging->send($message);

    return $messages;
  }

    public function pesan()
    {

        // This assumes that you have placed the Firebase credentials in the same directory
        // as this PHP file.
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/vendor/poihrd-418d3-ee60601e5cad.json');

        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            // The following line is optional if the project id in your credentials file
            // is identical to the subdomain of your Firebase project. If you need it,
            // make sure to replace the URL with the URL of your project.
            ->withDatabaseUri('https://poihrd-418d3.firebaseio.com/')
            ->create();

        $database = $firebase->getDatabase();

        $showData = $database->getReference("pesan");

        $result = $showData->getValue();
        return $result;
    }

    public function notif()
    {
        // This assumes that you have placed the Firebase credentials in the same directory
        // as this PHP file.
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/vendor/poihrd-418d3-ee60601e5cad.json');

        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            // The following line is optional if the project id in your credentials file
            // is identical to the subdomain of your Firebase project. If you need it,
            // make sure to replace the URL with the URL of your project.
            ->withDatabaseUri('https://poihrd-418d3.firebaseio.com/')
            ->create();

        $database = $firebase->getDatabase();

        $showData = $database->getReference("notif");

        $result = $showData->getValue();
        return $result;
    }

    public function configDb()
    {
        // This assumes that you have placed the Firebase credentials in the same directory
        // as this PHP file.
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/vendor/poihrd-418d3-ee60601e5cad.json');

        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            // The following line is optional if the project id in your credentials file
            // is identical to the subdomain of your Firebase project. If you need it,
            // make sure to replace the URL with the URL of your project.
            ->withDatabaseUri('https://poihrd-418d3.firebaseio.com/')
            ->create();

        $database = $firebase->getDatabase();

        return $database;

    }

    public function insert($data)
    {
        $database = self::configDb();

        $newPost = $database->getReference('denyanotif')->push($data);

        $result = $newPost->getValue();
        $newPost->remove();
        return $result;

    }

}
