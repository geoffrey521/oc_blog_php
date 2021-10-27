<?php

use App\Controller\TmpControllerFactory;
use App\Model\Session;

$controller = new TmpControllerFactory();
TmpControllerFactory::getAuth()->logout();
Session::getInstance()->setFlash('success', 'Vous avez été déconnecté');
$controller->redirectTo('login');