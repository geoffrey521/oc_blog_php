<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Icontroller;
use App\Model\Category;
use App\Model\Validator;
use App\Repository\CategoryRepository;

class CategoryController extends Controller implements Icontroller
{

    public function createCategory()
    {
        if (!empty($_POST)) {
            $validator = new Validator($_POST);
            $validator->isNotEmpty('name', 'Veuillez saisir un nom de catégorie');
            $category = new Category();
            if ($validator->isValid()) {
                $category->setName($_POST['name']);
            }
            $category->add();
            $this->session->setFlash('success', 'La catégorie a bien été créée');
            $this->redirectTo('user', 'manage_categories');
            $errors = $validator->getErrors();
            foreach ($errors as $error) {
                $this->session->setFlash('danger', $error);
            }
            $this->redirectTo('user', 'manage_categories');
        }
        if (isset($_SESSION['auth'])) {
            echo $this->twig->render('/admin/category/create.html.twig');
        }
    }

    public function deleteCategory($id)
    {
        $deleted = CategoryRepository::deleteById($id);
        if ($deleted == true) {
            $this->session->setFlash('success', 'La catégorie a bien été supprimée');
            $this->redirectTo('user', 'manage_categories');
        }
        $this->session->setFlash('danger', "La catégorie n'a pas pu être supprimée");
        $this->redirectTo('user', 'manage_categories');
    }
}
