<?php
namespace App\Controllers;

use App\Models\User AS UserModel;
use System\Core\Response;
use System\Core\Language;
use Psr\Http\Message\ResponseInterface;

class User {
    public function index(): ResponseInterface {
        $users = UserModel::all();
        return Response::json($users);
    }

    public function showPage(int $id): ResponseInterface {
        $user = UserModel::find($id);

        if (empty($user)) {
            return Response::html(view_render_html("<h4>" . Language::get("app.pages.users.not-found") . "</h4>"), 404);
        }

        $title = Language::get("app.pages.users.profile");
        view_share('title', $title);

        return Response::html(view_render_vue('users/Profile', [
            'title' => $title,
            'user' => $user,
            'labels' => [
                'id' => Language::get("app.pages.users.profile.id"),
                'name' => Language::get("app.pages.users.profile.name"),
                'email' => Language::get("app.pages.users.profile.email"),
                'backHome' => Language::get("app.back.home"),
            ],
            'urls' => [
                'home' => site_url(),
            ],
        ], null, ['app.pages.users', 'app.back']));
    }

    public function redirectToList(): ResponseInterface {
        return Response::redirect('/api/admin/users');
    }
}
