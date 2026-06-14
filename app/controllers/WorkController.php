<?php
/**
 * WorkController — /work
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class WorkController extends Controller
{
    public function index(): void
    {
        $profile = $this->model('Profile');

        $this->view('work/index', [
            'pageTitle' => 'Work — Judith Afriyie',
            'activeNav' => 'work',
            'projects'  => $profile->projects(),
        ]);
    }
}
