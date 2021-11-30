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
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;

class PostController extends Controller
{

    /*
     * Show page showing all posts
     */
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

    /**
     * Singlepost page
     * @param $slug
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function singlePost($slug)
    {
        $post = null;
        $comments = null;
        $user = null;
        if (isset($slug)) {
            $post = PostRepository::findBySlug($slug);
            $category = CategoryRepository::findById($post->getCategoryId());
            $post->setCategory($category->getName());
            $comments = CommentRepository::findAllCommentsByPostId($post->getId());
        }
        if (empty($post)) {
            $this->redirectTo('post');
        }
        if ($this->session->isLogged()) {
            $auth = $this->session->getUser();
            $user = UserRepository::findUserById($auth['id']);
        }
        echo $this->twig->render(
            '/front/single-post.html.twig',
            [
                'post' => $post,
                'comments' => $comments,
                'user' => $user
            ]
        );
    }

    /**
     * Create a new blog post
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
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

    /**
     * Edit blog post
     * @param $id
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
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

    /**
     * Delete blog post
     * @param $id
     */
    public function delete($id)
    {
        $deleted = PostRepository::deleteById($id);
        if ($deleted === true) {
            $this->session->setFlash('success', "L'article à bien été supprimé");
            $this->redirectTo('user', 'manage_posts');
        }
        $this->session->setFlash('danger', "L'article n'a pas pu être supprimé");
        $this->redirectTo('user', 'manage_posts');
    }

    /**
     * Delete blog post image
     * @param $id
     */
    public function deletePostImage($id)
    {
        PostRepository::deleteImageByPostId($id);
        $this->redirectTo('user', 'edit', ['id' => $id]);
    }
}
