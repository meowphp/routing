<?php
declare(strict_types=1);

namespace Meow\Routing\Attributes;

use Meow\Routing\Exceptions\WrongRouteNameFormatException;
use Meow\Routing\Tools\Text;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Prefix
{
    public string $prefixName;

    public function __construct(string $prefixName)
    {
        if (!Text::startWith($prefixName, '\/') || Text::endsWith($prefixName, '\/')) {
            throw new WrongRouteNameFormatException('Prefix name must start with slash and must not end with slash');
        }

        $this->prefixName = $prefixName;
    }

    /**
     * @return string
     */
    public function getPrefixName(): string
    {
        return $this->prefixName;
    }
}