<?php
namespace App\Controllers;

use App\Models\User AS UserModel;
use System\Core\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class User {
    public function index(): ResponseInterface {
        $users = UserModel::all();
        return Response::json($users);
    }

    public function showPage(ServerRequestInterface $request): ResponseInterface {
        $user = UserModel::find((int) $request->getAttribute('id'));

        if (!$user) {
            return Response::html(view_render_html("<h4>User not found!</h4>"), 404);
        }
        return Response::html(view_render_page('user-profile', ['user' => $user]));
    }

    public function redirectToList(): ResponseInterface {
        return Response::redirect('/users');
    }
}