<?php
/**
 * SkillsController — /skills
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class SkillsController extends Controller
{
    public function index(): void
    {
        $profile = $this->model('Profile');

        $this->view('skills/index', [
            'pageTitle'  => 'Skills — Judith Afriyie',
            'activeNav'  => 'skills',
            'highlights' => $profile->highlights(),
            'skills'     => $profile->skills(),
        ]);
    }
}
