<?php

declare(strict_types=1);

namespace TpElections\View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigEngine
{
    private static TwigEngine $instance;

    private readonly Environment $twigEnvironment;

    public static function getOrCreateInstance(): static
    {
        if (isset(static::$instance) === false) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function renderTemplate(string $templateLocation, array $templateVariables): void
    {
        $this->twigEnvironment->display(
            name: $templateLocation,
            context: $templateVariables,
        );
    }

    private function __construct() {
        $this->twigEnvironment = $this->createTwigEnvironment();
    }

    private function createTwigEnvironment(): Environment
    {
        $twigTemplateLoader = new FilesystemLoader(paths: $_SERVER['DOCUMENT_ROOT'] . '/templates');
        $twigEnvironment = new Environment(
            loader: $twigTemplateLoader,
            options: [
                'strict_variables' => true,
            ],
        );
        $twigEnvironment->addGlobal(name: 'session_id', value: session_id());

        return $twigEnvironment;
    }
}
