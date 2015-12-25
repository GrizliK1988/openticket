<?php

namespace DG\OpenticketBundle\Tests\Unit\Entity;


use DG\OpenticketBundle\Entity\TranslationFilter;

/**
 * Class TranslationFilterTest
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TranslationFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $filter = (new TranslationFilter())->setDomain('domain')->setLocale('locale');
        $this->assertEquals(['domain' => 'domain', 'locale' => 'locale'], $filter->toArray());

        $filter = (new TranslationFilter())->setDomain('domain');
        $this->assertEquals(['domain' => 'domain'], $filter->toArray());

        $filter = (new TranslationFilter())->setLocale('locale');
        $this->assertEquals(['locale' => 'locale'], $filter->toArray());

        $filter = (new TranslationFilter())->setDomain('')->setLocale('');
        $this->assertEquals(['domain' => '', 'locale' => ''], $filter->toArray());
    }
}
