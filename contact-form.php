<?php
require_once 'phpmailer/PHPMailerAutoload.php';

$corporateEmail = 'Destini.Protich@jacuzzi.com';

$data = null;

if (isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email']) && isset($_POST['zipCode'])) {

    //check if any of the inputs are empty
    if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['email']) || empty($_POST['zipCode'])) {
        $data = array('success' => false, 'message' => 'Se il vous plaît remplir le formulaire complètement.');
        echo json_encode($data);
        exit;
    }

    //create an instance of PHPMailer for Corporate
    $mailToCorporate = new PHPMailer();

    $mailToCorporate->From = $_POST['email'];
    $mailToCorporate->FromName = $_POST['firstName'] . ' ' . $_POST['lastName'];
    $mailToCorporate->AddAddress( $corporateEmail );
    $mailToCorporate->Subject = 'J-500(tm) Purchase Request';
    $mailToCorporate->Body = "Name: " . $mailToCorporate->FromName 
//        . "\r\n\r\nStore: " . stripslashes($_POST['storeName'])
        . "\r\n\r\nPhone: " . stripslashes( isset($_POST['phone']) ? $_POST['phone'] : '' )
        . "\r\n\r\nZip Code: " . stripslashes($_POST['zipCode'])
//        . "\r\n\r\nTub Model: " . stripslashes($_POST['tubName'])
        . "\r\n\r\nShell Color: " . stripslashes( isset($_POST['shellColor']['name']) ? $_POST['shellColor']['name'] : '' )
        . "\r\n\r\nSkirt Color: " . stripslashes( isset($_POST['skirtColor']['name']) ? $_POST['skirtColor']['name'] : '' );

    if (isset($_POST['ref'])) {
        $mailToCorporate->Body .= "\r\n\r\nRef: " . $_POST['ref'];
    }

    if(!$mailToCorporate->send()) {
        $data .= array('success' => false, 'message' => "Message n'a pas pu être envoyé. Mailer erreur: " . $mailToCorporate->ErrorInfo);
        echo json_encode($data);
        exit;
    }

    //create an instance of PHPMailer for Dealer
    $mailToDealer = new PHPMailer();

    $mailToDealer->From = $corporateEmail;
    $mailToDealer->FromName = 'Jacuzzi';
    $mailToDealer->AddAddress( $_POST['email'] );
    $mailToDealer->Subject = 'Le Jacuzzi J-500 (mc) Collection: conception révolutionnaire, la performance légendaire';
    $mailToDealer->Body = "Merci de votre intérêt pour la collection Jacuzzi J-500 (mc). Restez à l'écoute pour l'annonce du lancement officiel du produit, ainsi que des mises à jour sur la façon dont vous pouvez être le premier à voir le J-500 (mc) pour vous-même, et être le premier à posséder ce révolutionnaire collection limitée de bain à remous de disponibilité.";

    if (isset($_POST['ref'])) {
        $mailToDealer->Body .= "\r\n\r\nRef: " . $_POST['ref'];
    }

    if(!$mailToDealer->send()) {
        $data .= array('success' => false, 'message' => "Message n'a pas pu être envoyé. Mailer erreur: " . $mailToDealer->ErrorInfo);
        echo json_encode($data);
        exit;
    }

    $data = array('success' => true, 'message' => 'Merci! Nous avons reçu votre message.');
    echo json_encode($data);

} else {

    $data = array('success' => false, 'message' => 'Se il vous plaît remplir le formulaire complètement.');
    echo json_encode($data);

}