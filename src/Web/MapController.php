<?php
namespace F3\Commuter\Web;

class MapController
{
    private $twig;
    private $map_key;

    public function __construct(\Twig_Environment$twig, string $map_key)
    {
        $this->twig = $twig;
        $this->map_key = $map_key;
    }

    public function renderMap(string $map_name)
    {
        return $this->twig
            ->load('map.twig')
            ->render([
                'map_key' => $this->map_key,
                'map_name' => $map_name,
            ]);
    }
}
