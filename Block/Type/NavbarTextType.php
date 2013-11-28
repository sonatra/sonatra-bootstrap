<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Block\Type;

/**
 * Navbar Text Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class NavbarTextType extends AbstractNavbarItemType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'paragraph';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'navbar_text';
    }
}