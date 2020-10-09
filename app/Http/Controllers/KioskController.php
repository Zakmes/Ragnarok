<?php

namespace App\Http\Controllers;

use App\Domains\Activity\Models\Activity;
use App\Domains\Users\Models\Role;
use App\User;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class KioskController
 *
 * @package App\Http\Controllers
 */
class KioskController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'kiosk']);
    }

    public function __invoke(): Renderable
    {
        return view('kiosk', [
            'activities' => Activity::orderBy('id', 'DESC')->limit(5)->get(),
            'users' => User::orderBy('id', 'DESC')->limit(5)->get(),
            'roles' => Role::orderBy('id', 'DESC')->limit(5)->get()
        ]);
    }
}
