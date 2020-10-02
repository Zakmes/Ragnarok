<?php

namespace App\Http\Controllers;

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
        return view('kiosk');
    }
}
