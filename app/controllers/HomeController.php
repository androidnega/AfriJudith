<?php
/**
 * HomeController — single-screen landing.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $profile = $this->model('Profile');

        $this->view('home/index', [
            'pageTitle' => 'Judith Afriyie — Data Analyst & Web Developer',
            'activeNav' => 'home',
            'bodyClass' => 'is-landing',
            'profile'   => $profile->get(),
            'socials'   => $profile->socials(),
        ]);
    }
}
