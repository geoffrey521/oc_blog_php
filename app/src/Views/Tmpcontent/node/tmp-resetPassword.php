<?php
$controller = new \App\Controller\TmpControllerFactory();

if (isset($_GET['id']) && isset($_GET['token'])) {
    $auth = \App\Controller\TmpControllerFactory::getAuth();
    $db = \App\Model\ConnectDB::getDatabase();
    $user = $auth->checkResetToken($db, $_GET['id'], $_GET['token']);

    if ($user) {
        if (!empty($_POST)) {
            $validator = new \App\Model\Validator($_POST);
            $validator->isConfirmed('password');
            if ($validator->isValid()) {
                $password = $auth->hashPassword($_POST['password']);
                $db->query(
                    'UPDATE user SET password = ?, reset_token = NULL, reset_at = NULL WHERE id = ?',
                    [$password, $_GET['id']]
                );
                $auth->connect($user);
                \App\Model\Session::getInstance()->setFlash('success', 'Votre mot de passe a été réinitialisée');
                $controller->redirectTo('account');
            }
        }
    } else {
        \App\Model\Session::getInstance()->setFlash('danger', 'Lien invalide');
        $controller->redirectTo('login');
    }
} else {
    $controller->redirectTo('login');
}

?>

<?php require_once __DIR__ . '/tmp-header.php'; ?>
<main class="mb-4">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2>Réinitialisation du mot de passe</h2>
                <div class="my-5">
                    <form method="post">
                        <div class="form-floating">
                            <input type="password" name="password" class="form-control"
                                   placeholder="Nouveau de mot de passe" required/>
                            <label for="password">Nouveau mot de passe</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" name="password_confirm" class="form-control"
                                   placeholder="Confirmation du nouveau mot de passe" required/>
                            <label for="password_confirm">Confirmation du nouveau mot de passe</label>
                        </div>
                        <button class="btn btn-primary">Réinitialiser le mot de passe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require __DIR__ . '/tmp-footer.php'; ?>
