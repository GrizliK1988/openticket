<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Entity;


/**
 * Class AbstractFilter
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
abstract class AbstractFilter implements NormalizableFilterInterface
{
    public function toArray(): array
    {
        $properties = get_object_vars($this);
        $notNullProperties = array_filter($properties, function ($value) {
            return $value !== null;
        });

        return $notNullProperties;
    }
}