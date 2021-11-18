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
        echo $this->twig->render(
            '/front/posts.html.twig',
            [
                'posts' => $posts
            ]
        );
    }

    public function singlePost($slug)
    {
        $post = null;
        if (isset($slug)) {
            $post = PostRepository::findBySlug($slug);
            $category = CategoryRepository::findById($post->getCategoryId());

            $post->setCategory($category->getName());
        }
        if (empty($post)) {
            $this->redirectTo('post');
        }
        echo $this->twig->render(
            '/front/single-post.html.twig',
            [
                'post' => $post
            ]
        );
    }

    public function category()
    {
        if (isset($_SESSION['auth'])) {
            echo $this->twig->render(
                '/front/category.html.twig',
                [
                    'session' => $this->session,
                    'flashes' => $this->session->getFlashes(),
                ]
            );
            return;
        }
        echo $this->twig->render(
            '/front/category.html.twig',
            [
                'session' => $this->session,
                'flashes' => $this->session->getFlashes(),
            ]
        );
    }

    public function create()
    {
        if (!empty($_POST)) {
            $validator = new Validator(array_merge($_POST, $_FILES));
            $validator->validatePost();
            if ($validator->isValid()) {
                $user = $this->session->getUser();
                $post = Post::setPost($user);
                $post->add();
                $this->session->setFlash('success', "Article créé avec succès");
                $this->redirectTo('user', 'manage_posts');
            }
            $errors = $validator->getErrors();
            foreach ($errors as $error) {
                $this->session->setFlash('danger', $error);
            }
            $this->redirectTo('user', 'create');
        }

        if (isset($_SESSION['auth'])) {
            $categories = CategoryRepository::findAll();
            echo $this->twig->render(
                '/admin/post/create.html.twig',
                [
                    'categories' => $categories
                ]
            );
        }
    }

    public function edit($id)
    {
        $post = PostRepository::findById($id);
        if (!empty($_POST)) {
            $validator = new Validator(array_merge($_POST, $_FILES));
            $validator->validatePost();
            if ($validator->isValid()) {
                $user = $this->session->getUser();
                $post = Post::setPost($user);
                $post->edit($id);
                $this->session->setFlash('success', "Article modifié avec succès");
                $this->redirectTo('user', 'manage_posts');
            }
            $errors = $validator->getErrors();
            foreach ($errors as $error) {
                $this->session->setFlash('danger', $error);
            }
        }
        if (isset($_SESSION['auth'])) {
            $categories = CategoryRepository::findAll();
            echo $this->twig->render(
                '/admin/post/edit.html.twig',
                [
                    'post' => $post,
                    'categories' => $categories
                ]
            );
        }
    }

    public function delete($id)
    {
        $deleted = PostRepository::deleteById($id);
        if ($deleted == true) {
            $this->session->setFlash('success', "L'article à bien été supprimé");
            $this->redirectTo('user', 'manage_posts');
        }
        $this->session->setFlash('danger', "L'article n'a pas pu être supprimé");
        $this->redirectTo('user', 'manage_posts');
    }

    public function deletePostImage($id)
    {
        PostRepository::deleteImageByPostId($id);
        $this->redirectTo('user', 'edit', ['id' => $id]);
    }
}
