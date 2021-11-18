<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\CustomPage;
use App\Model\Post;
use App\Model\Validator;
use App\Repository\CustomPageRepository;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class CustomPageController extends Controller
{

    public function showCustomPage($slug)
    {
        if (isset($slug)) {
            $page = CustomPageRepository::findBySlug($slug);
            if (!$page->getPublished()) {
                $this->redirectTo('front', 'home');
            }
            echo $this->twig->render(
                '/pages/custom-page.html.twig',
                [
                'page' => $page
                ]
            );
        }
    }

    public function createPage()
    {
        if (!empty($_POST)) {
            $validator = new Validator(array_merge($_POST, $_FILES));
            $page = new CustomPage();
            $validator->isNotEmpty('name', 'La page doit avoir un nom');
            $validator->isNotEmpty('title', 'La page doit avoir un titre');
            $validator->isNotEmpty('content', 'La page doit avoir un contenu');
            $validator->isExist('name', CustomPage::getTableName(), 'Une page détient déjà ce nom');
            if (!empty($_FILES['image']['name'])) {
                $validator->isImageValid('image', 'Image invalide: ');
            }
            if ($validator->isValid()) {
                $page->setName($_POST['name'])
                    ->setTitle($_POST['title'])
                    ->setCatchPhrase($_POST['catchPhrase'])
                    ->setImage($_FILES['image'])
                    ->setContentTitle($_POST['contentTitle'])
                    ->setContent($_POST['content']);
                if (array_key_exists('displayNavbar', $_POST)) {
                    $page->setDisplayNavbar($_POST['displayNavbar']);
                }
                if (array_key_exists('displayFooter', $_POST)) {
                    $page->setDisplayFooter($_POST['displayFooter']);
                }
                if (array_key_exists('published', $_POST)) {
                    $page->setPublished($_POST['published']);
                }
                CustomPageRepository::uploadImage($_FILES['image']);
                $page->add();
                $this->session->setFlash('success', 'La page a bien été créée');
                $this->redirectTo('user', 'manage_pages');
            }
            $errors = $validator->getErrors();
            foreach ($errors as $error) {
                $this->session->setFlash('danger', $error);
            }
            $this->redirectTo('user', 'manage_pages');
        }
        if (isset($_SESSION['auth'])) {
            echo $this->twig->render('/admin/page/create.html.twig');
        }
    }

    public function editPage($id)
    {
        $page = CustomPageRepository::findById($id);
        if (!empty($_POST)) {
            $validator = new Validator(array_merge($_POST, $_FILES));
            $validator->isNotEmpty('title', 'La page doit avoir un titre');
            $validator->isNotEmpty('content', 'La page doit avoir un contenu');
            if (!empty($_FILES['image']['name'])) {
                $validator->isImageValid('image', 'Image invalide: ');
            }
            if ($validator->isValid()) {
                $checkboxes = $page->verifyCheckedBoxes(['displayNavbar', 'displayFooter', 'published'], $_POST);
                $page->setTitle($_POST['title'])
                    ->setCatchPhrase($_POST['catchPhrase'])
                    ->setContentTitle($_POST['contentTitle'])
                    ->setContent($_POST['content'])
                    ->setDisplayNavbar($checkboxes['displayNavbar'])
                    ->setDisplayFooter($checkboxes['displayFooter'])
                    ->setPublished($checkboxes['published']);
                if (!empty($_FILES['image']['name'])) {
                    $page->setImage($_FILES['image']);
                    CustomPageRepository::uploadImage($_FILES['image']);
                }
                $page->edit($id);
                $this->session->setFlash('success', 'La page a bien été modifiée');
                $this->redirectTo('user', 'manage_pages');
            }
            $errors = $validator->getErrors();
            foreach ($errors as $error) {
                $this->session->setFlash('danger', $error);
            }
        }

        if (isset($_SESSION['auth'])) {
            echo $this->twig->render(
                '/admin/page/edit.html.twig',
                [
                'page' => $page
                ]
            );
        }
    }

    public function deletePage($id)
    {
        $deleted = CustomPageRepository::deleteById($id);
        if ($deleted == true) {
            $this->session->setFlash('success', 'La page a bien été supprimée');
            $this->redirectTo('user', 'manage_pages');
        }
        $this->session->setFlash('danger', "La page n'a pas pu être supprimée");
        $this->redirectTo('user', 'manage_pages');
    }

    public function deletePageImage($id)
    {
        CustomPageRepository::deleteImageByPageId($id);
        $this->redirectTo('user', 'edit_page', ['id' => $id]);
    }
}
