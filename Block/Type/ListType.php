<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Bootstrap\Block\Type;

use Fxp\Component\Block\AbstractType;
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * List Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class ListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $tag = 'ul';

        if ('ordered' === $options['style']) {
            $tag = 'ol';
        } elseif ('description' === $options['style']) {
            $tag = 'dl';
        }

        $view->vars = array_replace($view->vars, [
            'style' => $options['style'],
            'tag' => $tag,
            'unstyled' => $options['unstyled'],
            'inline' => $options['inline'],
            'horizontal' => $options['horizontal'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'style' => 'unordered',
            'unstyled' => false,
            'inline' => false,
            'horizontal' => false,
        ]);

        $resolver->setAllowedTypes('style', 'string');
        $resolver->setAllowedTypes('unstyled', 'bool');
        $resolver->setAllowedTypes('inline', 'bool');
        $resolver->setAllowedTypes('horizontal', 'bool');

        $resolver->setAllowedValues('style', ['unordered', 'ordered', 'description']);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'list';
    }
}
