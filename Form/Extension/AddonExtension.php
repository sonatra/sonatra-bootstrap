<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Bootstrap\Form\Extension;

use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Fxp\Component\Block\Util\BlockUtil;
use Fxp\Component\Bootstrap\Block\Type\ButtonType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Addon Form Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class AddonExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $prepend = $options['prepend'];
        $append = $options['append'];

        $view->vars = array_replace($view->vars, [
            'addon_attr' => $options['addon_attr'],
            'prepend_attr' => $options['prepend_attr'],
            'append_attr' => $options['append_attr'],
            'prepend_type' => $this->definedType($prepend, $options['prepend_type']),
            'append_type' => $this->definedType($append, $options['append_type']),
        ]);

        // prepend
        if (\is_string($prepend)) {
            $view->vars['prepend_string'] = $prepend;
        } elseif ($prepend instanceof FormInterface) {
            $view->vars['prepend_form'] = $prepend->createView($view);
        } elseif ($prepend instanceof BlockInterface) {
            $view->vars['prepend_block'] = $prepend->createView($this->createBlockView($view));
        }

        // append
        if (\is_string($append)) {
            $view->vars['append_string'] = $append;
        } elseif ($append instanceof FormInterface) {
            $view->vars['append_form'] = $append->createView($view);
        } elseif ($append instanceof BlockInterface) {
            $view->vars['append_block'] = $append->createView($this->createBlockView($view));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'prepend' => null,
                'append' => null,
                'addon_attr' => [],
                'prepend_attr' => [],
                'append_attr' => [],
                'prepend_type' => null,
                'append_type' => null,
            ]
        );

        $resolver->addAllowedTypes('prepend', ['null', 'string', 'Symfony\Component\Form\FormInterface', 'Fxp\Component\Block\BlockInterface']);
        $resolver->addAllowedTypes('append', ['null', 'string', 'Symfony\Component\Form\FormInterface', 'Fxp\Component\Block\BlockInterface']);
        $resolver->addAllowedTypes('addon_attr', 'array');
        $resolver->addAllowedTypes('prepend_attr', 'array');
        $resolver->addAllowedTypes('append_attr', 'array');
        $resolver->addAllowedTypes('prepend_type', ['null', 'string']);
        $resolver->addAllowedTypes('append_type', ['null', 'string']);

        $resolver->addAllowedValues('prepend_type', [null, 'addon', 'btn']);
        $resolver->addAllowedValues('append_type', [null, 'addon', 'btn']);
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [FormType::class];
    }

    /**
     * Create the block view with the form view.
     *
     * @param FormView $view The form view
     *
     * @return BlockView
     */
    protected function createBlockView(FormView $view)
    {
        $bView = new BlockView();
        $bView->vars = $view->vars;
        $bView->vars['block'] = $bView;
        unset($bView->vars['form']);

        return $bView;
    }

    /**
     * @param string|FormInterface|BlockInterface $addon
     * @param string                              $type
     *
     * @return string The addon type
     */
    protected function definedType($addon, $type)
    {
        if (\is_string($addon)) {
            return null !== $type ? $type : 'addon';
        } elseif ($addon instanceof FormInterface) {
            return 'btn';
        } elseif ($addon instanceof BlockInterface) {
            if (null === $type && BlockUtil::isBlockType($addon, ButtonType::class)) {
                $type = 'btn';
            }

            return $type;
        }

        return 'addon';
    }
}
