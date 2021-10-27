<?php

use App\Controller\TmpControllerFactory;
use App\Model\ConnectDB;

$controller = new TmpControllerFactory();
$db = ConnectDB::getDatabase();

if (TmpControllerFactory::getAuth()->confirm($db, $_GET['id'], $_GET['token'], \App\Model\Session::getInstance())) {
    \App\Model\Session::getInstance()->setFlash('success', "Votre compte à bien été validé.");
    $controller->redirectTo('account');
} else {
    \App\Model\Session::getInstance()->setFlash('danger', "Ce lien de confirmation n'est plus valide");
    $controller->redirectTo('login');
}
