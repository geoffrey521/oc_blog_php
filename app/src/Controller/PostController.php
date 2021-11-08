<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Icontroller;
use App\Model\Category;
use App\Model\Database;
use App\Model\Post;
use App\Model\Session;
use App\Model\User;
use App\Model\Validator;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

class PostController extends Controller implements Icontroller
{

    public function showPosts()
    {
        $posts = PostRepository::findAll();
        echo $this->twig->render('/front/posts.html.twig', [
            'posts' => $posts
        ]);
    }

    public function singlePost($id)
    {
        $post = null;
        if (isset($id)) {
            $post = PostRepository::findById($id);
        }
        if (empty($post)) {
            $this->redirectTo('post');
        }
        echo $this->twig->render('/front/single-post.html.twig', [
            'post' => $post,
        ]);
    }

    public function category()
    {
        if (isset($_SESSION['auth'])) {
            echo $this->twig->render('/front/category.html.twig', [
                'session' => $this->session,
                'flashes' => $this->session->getFlashes(),
            ]);
        } else {
            echo $this->twig->render('/front/category.html.twig', [
                'session' => $this->session,
                'flashes' => $this->session->getFlashes(),
            ]);
        }
    }

    public function create()
    {
        if (!empty($_POST)) {
            $validator = new Validator(array_merge($_POST, $_FILES));
            $post = new Post();
            $validator->isImageValid('image', 'Image invalide: ');
            $validator->isNotEmpty('title', "Merci d'entrer un titre");
            $validator->isNotEmpty('lead', "Merci d'entrer un chapô");
            $validator->isNotEmpty('category', "Merci de sélectionner une catégorie");
            $validator->isNotEmpty('slug', "Merci d'entrer une référence");
            $validator->isNotEmpty('content', "L'article ne contient aucun contenu");
            if ($validator->isValid()) {
                // TODO create hydrate function to get user
                $user = $this->session->getUser();
                $post->setTitle($_POST['title'])
                    ->setLead($_POST['lead'])
                    ->setImage($_FILES['image'])
                    ->setAuthorId($user['id'])
                    ->setCategoryId($_POST['category'])
                    ->setSlug($_POST['slug'])
                    ->setContent($_POST['content']);
                PostRepository::upload($post);
                $post->add();
                $this->session->setFlash('success', "Article créé avec succès");
                $this->redirectTo('user', 'admin');
            }
            $errors = $validator->getErrors();
            foreach ($errors as $error) {
                $this->session->setFlash('danger', $error);
            }
            $this->redirectTo('user', 'admin');
        }

        if (isset($_SESSION['auth'])) {
            $categories = CategoryRepository::findAll();
            echo $this->twig->render('/admin/post/create.html.twig', [
                'categories' => $categories
            ]);
        }
    }

    public function delete()
    {
        $post = PostRepository::findById($_GET['id']);
        $deleted = $post->delete($post->getId());
        if ($deleted == true) {
            $this->session->setFlash('success', "L'article à bien été supprimé");
        } else {
            $this->session->setFlash('danger', "L'article n'a pas pu être supprimé");
        }
        $this->redirectTo('user', 'admin');
    }

    public function edit()
    {
        if (!empty($_POST)) {
            $post = new Post();
            $user = new User(Session::getInstance());
            $post->setTitle($_POST['title'])
                ->setLead($_POST['lead'])
                ->setAuthorId($user->getId())
                ->setCategoryId($_POST['categories'])
                ->setSlug($_POST['slug'])
                ->setContent($_POST['content']);
            $post->add();
            $this->session->setFlash('success', "Article créé avec succès");
        }

        $post = PostRepository::findById($_GET['id']);
        if (isset($_SESSION['auth'])) {
            $categories = CategoryRepository::findAll();
            if (!empty($_POST)) {
                echo $this->twig->render('/admin/post/edit.html.twig', [
                    'post' => $post,
                    'categories' => $categories
                ]);
            } else {
                echo $this->twig->render('/admin/post/edit.html.twig', [
                    'post' => $post,
                    'categories' => $categories
                ]);
            }
        }
    }
}
