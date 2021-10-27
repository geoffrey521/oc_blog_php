<?php
\App\Controller\TmpControllerFactory::getAuth()->restrict();
if(!empty($_POST)) {

    if($_POST['password'] != $_POST['password_confirm']) {
        $_SESSION['flash']['danger'] = "Les mots de passes ne sont pas identiques";
    } elseif (empty($_POST['password'])) {
        $_SESSION['flash']['danger'] = "Le mot de passe ne peut pas être vide";
    } else {
        $user_id = $_SESSION['auth']->id;
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $pdo->prepare('UPDATE user SET password = ?')->execute([$password]);
        $_SESSION['flash']['success'] = "Le mot de passe à bien été modifié";
    }

}
require_once __DIR__ . '/tmp-header.php';
?>

    <!-- Main Content-->
    <div class="container px-4 px-lg-5">
       <h2>Bonjour <?= $_SESSION['auth']->username; ?></h2>

        <form method="post">
            <div class="form-floating">
                <input type="password" name="password" class="form-control" placeholder="Changer de mot de passe" required />
                <label for="password">Changer de mot de passe</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password_confirm" class="form-control" placeholder="Confirmation du nouveau mot de passe" required />
                <label for="password_confirm">Confirmation du nouveau mot de passe</label>
            </div>
            <button class="btn btn-primary">Changer de mot de passe</button>
        </form>
    </div>

<?php require __DIR__ . '/tmp-footer.php'; ?>