<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Mapper;


/**
 * Traits\ConvertibleTrait, в котором toXml() собирает XML через XmlMapper
 */
trait ConvertibleTrait
{
    use \Traits\ConvertibleTrait;

    public function toXml()
    {
        return (new XmlMapper())->unmap($this);
    }
}
