<?php

use App\Controller\TmpControllerFactory;

require_once __DIR__ . '/../../../Controller/TmpControllerFactory.php';
$session= \App\Model\Session::getInstance();
$controller = new TmpControllerFactory();

if(!empty($_POST))
{
    $errors = [];
    $db = \App\Model\ConnectDB::getDatabase();
    $validator = new \App\Model\Validator($_POST);
    $validator->isAlpha('username', "Pseudo non rempli ou non valide (caractères alphanumérique et traits d'union uniquement)");
    if($validator->isValid()) {
        $validator->isUniq('username', $db,'user', 'Ce pseudo est déjà utilisé');
    }
    $validator->isAlpha('lastname', "Nom non rempli ou non valide (caractères alphanumérique et traits d'union uniquement)");
    $validator->isAlpha('firstname', "Prénom non rempli ou non valide (caractères alphanumérique et traits d'union uniquement)");
    $validator->isEmail('email', 'Email non valide');
    if($validator->isValid()) {
        $validator->isUniq('email', $db, 'user', "Un compte est déjà associé à cet email.");
    }
    $validator->isConfirmed('password', "Mot de passe non valide");
    $validator->isAgree('terms', "Vous devez accepter les conditions générales d'utilisation pour vous inscrire");


    if($validator->isValid()) {

        TmpControllerFactory::getAuth()->register(
                $db,
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['username'],
                $_POST['password'],
                $_POST['email']);


        \App\Model\Session::getInstance()->setFlash('success', 'Un email de confirmation vous a été envoyé pour valider votre compte.');
        $controller->redirectTo('login');
    } else {
        $errors = $validator->getErrors();
    }
}
?>

    <?php require __DIR__ . '/tmp-header.php'; ?>

    <!-- Main Content-->
    <div class="container px-4 px-lg-5">
        <main class="mb-4">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <h2>Inscription</h2>

                        <?php if(!empty($errors)) :?>
                        <div class="alert alert-danger">
                            <p>Erreur lors de votre enregistrement</p>
                            <ul>
                                <?php foreach ($errors as $error) : ?>
                                    <li><?= $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <div class="my-5">
                            <form id="register-form" method="POST" class="Form">
                                <div class="form-floating">
                                    <input class="form-control" id="register-username" name="username" type="text" placeholder="Entrez votre nom d'utilisateur..." required/>
                                    <label for="username">Pseudo</label>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="register-lastname" name="lastname" type="text" placeholder="Entrez votre prénom..." required />
                                    <label for="lastname">Nom</label>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="register-firstname" name="firstname" type="text" placeholder="Entrez votre nom..." required />
                                    <label for="firstname">Prénom</label>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="register-email" name="email" type="text" placeholder="Entrez votre email..." required />
                                    <label for="email">Email address</label>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="register-password" name="password" type="password" placeholder="Entrez votre mot de passe..." required />
                                    <label for="password">Mot de passe</label>
                                </div>
                                <div class="form-floating">
                                    <input class="form-control" id="register-confirm-password" name="password_confirm" type="password" placeholder="Entrez votre mot de passe..." required />
                                    <label for="password_confirm">Confirmez votre mot de passe</label>
                                </div>
                                <div class="form-floating Form-checkbox">
                                    <input class="form-check" id="register-terms" type="checkbox" name="terms" required />
                                    <label for="terms">En cochant cette case, je reconnais avoir pris connaissance de la charte RGPD et en accepte les termes</label>
                                </div>
                                <br />
                                <!-- Submit Button-->
                                <button class="btn btn-primary text-uppercase" id="submitButton" type="submit">M'inscrire</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php require __DIR__ . '/tmp-footer.php'; ?>


<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Core theme JS-->
<script src="assets/js/scripts.js"></script>
</body>
</html>