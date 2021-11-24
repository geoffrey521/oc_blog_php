<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\Comment;
use App\Model\Validator;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;

class CommentController extends Controller
{
    public function createComment($id)
    {
        $post = PostRepository::findById($id);
        if ($_POST) {
            $validator = new Validator($_POST);
            $validator->validateComment();
            if ($validator->isValid()) {
                $user = $this->session->getUser();
                $comment = new Comment();
                $comment->setContent($_POST['content'])
                    ->setAuthorId($user['id'])
                    ->setPostId($post->getId());
                $comment->add();
                $this->session->setFlash('success', "Commentaire soumis, en attente de validation d'un administrateur");
                $this->redirectTo('user', 'singlePost', ['slug' => $post->getSlug()]);
            }
        }
    }

    public function validateComment($id)
    {
        $comment = CommentRepository::findById($id);
        $comment->setStatus(2);
        $comment->editStatus($comment->getId());
        $this->session->setFlash('success', "Le commentaire est désormais visible");
        $this->redirectTo('user', 'manage_comments');
    }

    public function deleteComment($id)
    {
        $comment = CommentRepository::findById($id);
        $comment->setStatus(3);
        $comment->editStatus($comment->getId());
        $this->session->setFlash('success', "Le commentaire a été modéré, vous pouvez le retrouver dans l'historique");
        $this->redirectTo('user', 'manage_comments');
    }
}
