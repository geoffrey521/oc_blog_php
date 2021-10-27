<?php

$controller = new \App\Controller\TmpControllerFactory();
$auth = \App\Controller\TmpControllerFactory::getAuth();
$db = \App\Model\ConnectDB::getDatabase();
$auth->connectFromCookie($db);

if ($auth->getUser()) {
    $controller->redirectTo('account');
}

if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
    $user = $auth->login($db, $_POST['username'], $_POST['password'], isset($_POST['remember']));
    $session = \App\Model\Session::getInstance();
    if ($user) {
        $session->setFlash('success', 'Connexion réussie');
        $controller->redirectTo('account');
    } else {
        $session->setFlash('danger', 'identifiant ou mot de passe incorrecte');
    }
}
?>


<?php require_once __DIR__ . '/tmp-header.php'; ?>
<main class="mb-4">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2>Se connecter</h2>
                <div class="my-5">
                    <form id="register-form" method="POST">
                        <div class="form-floating">
                            <input class="form-control" id="login-username" name="username" type="text"
                                   placeholder="Entrez votre nom d'utilisateur..." required/>
                            <label for="username">Pseudo ou Email</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="login-password" name="password" type="password"
                                   placeholder="Entrez votre mot de passe..." required />
                            <label for="password">Mot de passe </label>
                            <a href="index.php?p=forget" class="text-muted">Mot de passe oublié ?</a>
                        </div>
                        <div class="form-floating Form-checkbox">
                            <input class="form-check" name="remember" type="checkbox" />
                            <label for="remember">Se souvenir de moi</label>
                        </div>
                        <br />
                        <button class="btn btn-primary text-uppercase"
                                id="submitButton" type="submit">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require __DIR__ . '/tmp-footer.php'; ?>
