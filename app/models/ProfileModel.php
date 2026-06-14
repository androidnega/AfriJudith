<?php
/**
 * ProfileModel — single source of truth for Judith's bio data.
 *
 * Returns plain arrays today. When a database is added, the public
 * API of this class stays exactly the same — only the internal
 * implementation switches to a SELECT.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class ProfileModel extends Model
{
    public function get(): array
    {
        return [
            'name'        => 'Judith Afriyie',
            'handle'      => 'afriJudith.online',
            'role'        => 'Data Analyst • Web Developer',
            'location'    => 'Takoradi, Ghana',
            'school'      => 'Takoradi Technical University',
            'department'  => 'Department of Computer Science',
            'year'        => 'Final Year Student',
            'email'       => 'hello@afrijudith.online',
            'bio'         => 'I turn raw data into clear, beautiful stories and craft web '
                           . 'experiences that are simple, fast, and a joy to use. Currently '
                           . 'finishing my final year in Computer Science at Takoradi Technical '
                           . 'University, where I focus on analytics, dashboards, and modern '
                           . 'full-stack development.',
            'available'   => true,
        ];
    }

    public function skills(): array
    {
        return [
            ['name' => 'Python',       'level' => 88, 'category' => 'data'],
            ['name' => 'SQL',          'level' => 92, 'category' => 'data'],
            ['name' => 'Power BI',     'level' => 85, 'category' => 'data'],
            ['name' => 'Excel',        'level' => 95, 'category' => 'data'],
            ['name' => 'HTML / CSS',   'level' => 94, 'category' => 'web'],
            ['name' => 'JavaScript',   'level' => 82, 'category' => 'web'],
            ['name' => 'PHP',          'level' => 80, 'category' => 'web'],
            ['name' => 'MySQL',        'level' => 86, 'category' => 'web'],
        ];
    }

    public function stats(): array
    {
        return [
            ['label' => 'Projects',   'value' => '20+'],
            ['label' => 'Dashboards', 'value' => '15+'],
            ['label' => 'Clients',    'value' => '10+'],
        ];
    }

    public function highlights(): array
    {
        return [
            [
                'icon'  => 'chart',
                'title' => 'Data Analytics',
                'text'  => 'Turning messy datasets into clear dashboards and decisions.',
            ],
            [
                'icon'  => 'code',
                'title' => 'Web Development',
                'text'  => 'Building modern, responsive websites with clean code.',
            ],
            [
                'icon'  => 'spark',
                'title' => 'Growth & Innovation',
                'text'  => 'Always learning, shipping, and refining for real impact.',
            ],
        ];
    }

    public function projects(): array
    {
        return [
            [
                'title'    => 'Student Performance Dashboard',
                'category' => 'Data Analytics',
                'icon'     => 'fa-solid fa-chart-line',
                'summary'  => 'An interactive Power BI dashboard turning exam data into actionable insights for lecturers and students.',
                'stack'    => ['Power BI', 'Excel', 'DAX'],
                'url'      => '#',
            ],
            [
                'title'    => 'Campus Events Portal',
                'category' => 'Web Development',
                'icon'     => 'fa-solid fa-calendar-days',
                'summary'  => 'A PHP & MySQL platform for discovering, registering, and managing events across the university campus.',
                'stack'    => ['PHP', 'MySQL', 'JavaScript'],
                'url'      => '#',
            ],
            [
                'title'    => 'SME Sales Tracker',
                'category' => 'Data + Web',
                'icon'     => 'fa-solid fa-cash-register',
                'summary'  => 'A lightweight web app that helps small businesses log and visualize daily sales with simple, clear charts.',
                'stack'    => ['PHP', 'Chart.js', 'MySQL'],
                'url'      => '#',
            ],
            [
                'title'    => 'Mobile Money Insights',
                'category' => 'Data Analytics',
                'icon'     => 'fa-solid fa-mobile-screen-button',
                'summary'  => 'A Python pipeline that parses MoMo SMS exports and produces monthly spending dashboards.',
                'stack'    => ['Python', 'Pandas', 'Plotly'],
                'url'      => '#',
            ],
        ];
    }

    public function socials(): array
    {
        return [
            [
                'name'     => 'GitHub',
                'url'      => 'https://github.com/AfriyieJud',
                'icon'     => 'fa-brands fa-github',
                'external' => true,
            ],
            [
                'name'     => 'LinkedIn',
                'url'      => 'https://www.linkedin.com/in/judith-afriyie-mensah-09b18b317',
                'icon'     => 'fa-brands fa-linkedin-in',
                'external' => true,
            ],
            [
                'name'     => 'Twitter',
                'url'      => 'https://x.com/afriyie_judith2',
                'icon'     => 'fa-brands fa-x-twitter',
                'external' => true,
            ],
            [
                'name'     => 'WhatsApp',
                'url'      => 'https://wa.me/233599100504',
                'icon'     => 'fa-brands fa-whatsapp',
                'external' => true,
            ],
            [
                'name'     => 'Email',
                'url'      => 'mailto:' . $this->get()['email'],
                'icon'     => 'fa-solid fa-envelope',
                'external' => false,
            ],
        ];
    }
}
