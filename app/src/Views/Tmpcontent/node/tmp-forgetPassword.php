<?php

if (!empty($_POST) && !empty($_POST['email'])) {
    $controller = new \App\Controller\TmpControllerFactory();
    $session = \App\Model\Session::getInstance();
    $db = \App\Model\ConnectDB::getDatabase();
    $auth = \App\Controller\TmpControllerFactory::getAuth();
    if ($auth->resetPassword($db, $_POST['email'])) {
        $session->setFlash('success', 'Un mail pour réinitialiser votre mot de passe vous a été envoyé');
        $controller->redirectTo('login');
    } else {
        $session->setFlash('danger', 'Aucun compte n\'est associé à cet email');
    }
}
?>


<?php require_once __DIR__ . '/tmp-header.php'; ?>
<main class="mb-4">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2>Mot de passe oublié</h2>
                <p>Entrez votre email pour réinitialiser votre mot de passe</p>
                <div class="my-5">
                    <form id="register-form" method="POST">
                        <div class="form-floating">
                            <input class="form-control"
                                   id="login-username" name="email" type="email" placeholder="Email..." required/>
                            <label for="email">Email</label>
                        </div>
                        <br />
                        <button class="btn btn-primary text-uppercase" id="submitButton" type="submit">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require __DIR__ . '/tmp-footer.php'; ?>