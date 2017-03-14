<?php
namespace F3\Commuter\Web;

use Silex\Application;

class MapController
{
    private $twig;
    private $map_key;

    public function __construct(\Twig_Environment $twig, string $map_key)
    {
        $this->twig = $twig;
        $this->map_key = $map_key;
    }

    public function redirectToRandomMap(Application $app)
    {
        return $app->redirect('/map/' . uniqid());
    }

    public function renderMap(string $map_name)
    {
        return $this->twig
            ->load('map.twig')
            ->render([
                'map_key' => $this->map_key,
                'config'  => [
                    'map_name' => $map_name,
                ],
            ]);
    }
}
