<?php
/**
 * AboutController — /about
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class AboutController extends Controller
{
    public function index(): void
    {
        $profile = $this->model('Profile');

        $this->view('about/index', [
            'pageTitle' => 'About — Judith Afriyie',
            'activeNav' => 'about',
            'profile'   => $profile->get(),
            'stats'     => $profile->stats(),
        ]);
    }
}
