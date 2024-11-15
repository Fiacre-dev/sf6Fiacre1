<?php
namespace App\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyCustomTwigExtensions extends AbstractExtension {
    public function getFilters(): array
    {
        return[
            new TwigFilter(
                "defaultImages",
                [$this,'defaultImage']
            ),
        ];
    }
    public function defaultImage(string $path):string{
        if(strlen(trim($path))==0){
            return'fiacre.jpeg';

        }
        return $path;
    }
}
