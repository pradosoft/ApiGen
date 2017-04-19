<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file LICENSE that was distributed with this source code.
 */

namespace ApiGen\Templating\Filters\Helpers;

use ApiGen\Contracts\Parser\Reflection\ClassReflectionInterface;
use ApiGen\Contracts\Parser\Reflection\ConstantReflectionInterface;
use ApiGen\Contracts\Parser\Reflection\ElementReflectionInterface;
use ApiGen\Contracts\Parser\Reflection\FunctionReflectionInterface;
use ApiGen\Contracts\Parser\Reflection\MethodReflectionInterface;
use ApiGen\Contracts\Parser\Reflection\PropertyReflectionInterface;
use Nette\Utils\Html;
use UnexpectedValueException;

class ElementLinkFactory
{

    /**
     * @var ElementUrlFactory
     */
    private $elementUrlFactory;

    /**
     * @var LinkBuilder
     */
    private $linkBuilder;


    public function __construct(ElementUrlFactory $elementUrlFactory, LinkBuilder $linkBuilder)
    {
        $this->elementUrlFactory = $elementUrlFactory;
        $this->linkBuilder = $linkBuilder;
    }


    /**
     * @return string
     */
    public function createForElement(ElementReflectionInterface $element, array $classes = [], $overrideElementDescription = '')
    {
        if ($element instanceof ClassReflectionInterface) {
            return $this->createForClass($element, $classes, $overrideElementDescription);
        } elseif ($element instanceof MethodReflectionInterface) {
            return $this->createForMethod($element, $classes, $overrideElementDescription);
        } elseif ($element instanceof PropertyReflectionInterface) {
            return $this->createForProperty($element, $classes, $overrideElementDescription);
        } elseif ($element instanceof ConstantReflectionInterface) {
            return $this->createForConstant($element, $classes, $overrideElementDescription);
        } elseif ($element instanceof FunctionReflectionInterface) {
            return $this->createForFunction($element, $classes, $overrideElementDescription);
        }

        throw new UnexpectedValueException(
            'Descendant of ApiGen\Reflection\Reflection class expected. Got "'
            . get_class($element) . ' class".'
        );
    }


    /**
     * @return string
     */
    private function createForClass(ClassReflectionInterface $reflectionClass, array $classes, $overrideElementDescription)
    {
        return $this->linkBuilder->build(
            $this->elementUrlFactory->createForClass($reflectionClass),
            $overrideElementDescription ? $overrideElementDescription : $reflectionClass->getName(),
            true,
            $classes
        );
    }


    /**
     * @return string
     */
    private function createForMethod(MethodReflectionInterface $reflectionMethod, array $classes, $overrideElementDescription)
    {
        return $this->linkBuilder->build(
            $this->elementUrlFactory->createForMethod($reflectionMethod),
            $overrideElementDescription ?
                $overrideElementDescription :
                $reflectionMethod->getDeclaringClassName() . '::' . $reflectionMethod->getName() . '()',
            false,
            $classes
        );
    }


    /**
     * @return string
     */
    private function createForProperty(PropertyReflectionInterface $reflectionProperty, array $classes, $overrideElementDescription)
    {
        $text = $overrideElementDescription ?
            $overrideElementDescription :
            $reflectionProperty->getDeclaringClassName() . '::' .
                Html::el('var')->setText('$' . $reflectionProperty->getName());

        return $this->linkBuilder->build(
            $this->elementUrlFactory->createForProperty($reflectionProperty),
            $text,
            false,
            $classes
        );
    }


    /**
     * @return string
     */
    private function createForConstant(ConstantReflectionInterface $reflectionConstant, array $classes, $overrideElementDescription)
    {
        $url = $this->elementUrlFactory->createForConstant($reflectionConstant);

        if($overrideElementDescription) {
            $text = $overrideElementDescription;
        } elseif ($reflectionConstant->getDeclaringClassName()) {
            $text = $reflectionConstant->getDeclaringClassName() . '::' .
                Html::el('b')->setText($reflectionConstant->getName());
        } else {
            $text = $this->getGlobalConstantName($reflectionConstant);
        }

        return $this->linkBuilder->build($url, $text, false, $classes);
    }


    /**
     * @return string
     */
    private function createForFunction(FunctionReflectionInterface $reflectionFunction, array $classes, $overrideElementDescription)
    {
        return $this->linkBuilder->build(
            $this->elementUrlFactory->createForFunction($reflectionFunction),
            $overrideElementDescription ?
                $overrideElementDescription :
                $reflectionFunction->getName() . '()',
            true,
            $classes
        );
    }


    /**
     * @return string
     */
    private function getGlobalConstantName(ConstantReflectionInterface $reflectionConstant)
    {
        if ($reflectionConstant->inNamespace()) {
            return $reflectionConstant->getNamespaceName() . '\\' .
                Html::el('b')->setText($reflectionConstant->getShortName());
        } else {
            return Html::el('b')->setText($reflectionConstant->getName());
        }
    }
}
