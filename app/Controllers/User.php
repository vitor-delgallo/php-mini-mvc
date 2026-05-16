<?php
namespace App\Controllers;

use App\Models\User AS UserModel;
use System\Core\Language;
use System\Core\Path;
use System\Core\Response;
use System\Core\View;
use Psr\Http\Message\ResponseInterface;

class User {
    public function index(): ResponseInterface {
        $users = UserModel::all();
        return Response::json($users);
    }

    public function showPage(int $id): ResponseInterface {
        $user = UserModel::find($id);

        if (empty($user)) {
            return Response::html(View::render_html("<h4>" . Language::get("app.pages.users.not-found") . "</h4>"), 404);
        }

        $title = Language::get("app.pages.users.profile");
        View::share('title', $title);

        return Response::html(View::render_vue('users/Profile', [
            'title' => $title,
            'user' => $user,
            'labels' => [
                'id' => Language::get("app.pages.users.profile.id"),
                'name' => Language::get("app.pages.users.profile.name"),
                'email' => Language::get("app.pages.users.profile.email"),
                'backHome' => Language::get("app.back.home"),
            ],
            'urls' => [
                'home' => Path::siteURL(),
            ],
        ], null, ['app.pages.users', 'app.back']));
    }

    public function redirectToList(): ResponseInterface {
        return Response::redirect('/api/admin/users');
    }
}
