<?php
namespace Src\Controllers;

use Twig\Environment;

class LandingController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render the landing page
     */
    public function index(): void
    {
        $features = [
            [
                'title' => 'Easy Management',
                'description' => 'Quickly create and track tickets for events or support requests.',
            ],
            [
                'title' => 'Real-time Updates',
                'description' => 'Stay informed with real-time ticket status updates and notifications.',
            ],
            [
                'title' => 'Secure Platform',
                'description' => 'Your data is safe and accessible only by authorized users.',
            ],
        ];

        $circles = [
            ['top' => '10%', 'left' => '5%', 'size' => '80px'],
            ['top' => '70%', 'left' => '80%', 'size' => '120px'],
        ];

        echo $this->twig->render('pages/landing.html.twig', [
            'features' => $features,
            'circles' => $circles,
            'year' => date('Y'),
        ]);
    }
}
