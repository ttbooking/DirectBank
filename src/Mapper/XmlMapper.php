<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Mapper;


use Common\ModelReflection\Enum\AnnotationEnum;
use Common\ModelReflection\ModelClass;
use Common\ModelReflection\ModelProperty;
use Common\Util\Validation;
use Mapper\XmlModelMapper;

/**
 * XmlModelMapper, который при сборке XML выводит свойства от базового класса к наследнику.
 *
 * Reflection отдаёт сначала свойства самого класса, потом унаследованные, а в типах-расширениях
 * XSD (xsd:extension) элементы базового типа идут раньше добавленных.
 */
class XmlMapper extends XmlModelMapper
{
    /**
     * Повторяет XmlModelMapper::unmapModel(), меняется только порядок свойств
     */
    protected function unmapModel(object $model): \stdClass
    {
        if (Validation::isEmpty($model)) {
            throw new \InvalidArgumentException('Model must be an object with properties.');
        }

        $unmappedObject = new \stdClass();
        foreach (self::orderedProperties(new ModelClass($model)) as $property) {
            $propertyKey = $property->getName();
            $propertyValue = $property->getPropertyValue();
            if (Validation::isEmpty($propertyValue)) {
                continue;
            }

            if ($property->getDocBlock()->hasAnnotation(AnnotationEnum::XML_ATTRIBUTE)) {
                $attributeKey = self::ATTR_KEY;
                $unmappedObject->{$attributeKey}[$propertyKey] = $propertyValue;
                continue;
            }

            if ($property->getDocBlock()->hasAnnotation(AnnotationEnum::XML_NODE_VALUE)) {
                $valueKey = self::VALUE_KEY;
                $unmappedObject->$valueKey = $propertyValue;
                continue;
            }

            $unmappedObject->$propertyKey = $this->unmapValueByType($property->getType(), $propertyValue);
        }

        return $unmappedObject;
    }

    /**
     * Свойства от самого базового класса к наследнику, внутри класса — в порядке объявления
     *
     * @return ModelProperty[]
     */
    protected static function orderedProperties(ModelClass $modelClass): array
    {
        $properties = $modelClass->getProperties();

        $depth = function (ModelProperty $property): int {
            $depth = 0;
            for ($class = $property->getProperty()->getDeclaringClass(); $class = $class->getParentClass(); ) {
                $depth++;
            }

            return $depth;
        };

        // usort стабилен с PHP 8.0: порядок объявления внутри класса сохраняется
        usort($properties, fn(ModelProperty $a, ModelProperty $b) => $depth($a) <=> $depth($b));

        return $properties;
    }
}
