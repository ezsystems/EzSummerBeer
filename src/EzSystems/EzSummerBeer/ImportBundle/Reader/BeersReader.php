<?php

namespace EzSystems\EzSummerBeer\ImportBundle\Reader;

use ArrayIterator;
use DateTime;
use Ddeboer\DataImport\Reader\ReaderInterface;

class BeersReader extends ArrayIterator implements ReaderInterface
{
    public function __construct($file, $offset = 0)
    {
        parent::__construct($this->loadData($file, $offset));
    }

    protected function loadData($file, $offset)
    {
        $data = [];
        foreach (json_decode(file_get_contents($file), true) as $i => $item) {
            if ($i < $offset) {
                continue;
            }

            if (isset($item['styleId'])) {
                $styleId = 'style-'.$item['styleId'];
            } elseif (isset($item['style']['id'])) {
                $styleId = 'style-'.$item['style']['id'];
            } else {
                $styleId = null;
            }

            $data[] = [
                '_remoteId' => 'beer-' . $item['id'],
                '_creationDate' => new DateTime($item['createDate']),
                '_styleId' => $styleId,
                '_glassId' => isset($item['glass']['id']) ? 'glass-'.$item['glass']['id'] : null,
                'name' => $item['name'],
                'description' => isset($item['description']) ? $item['description'] : null,
                'abv' => isset($item['abv']) ? (float)$item['abv'] : null,
                'ibu' => isset($item['ibu']) ? (int)$item['ibu'] : null,
                'glass' => isset($item['glass']) ? $item['glass'] : null,
                'is_organic' => isset($item['isOrganic']) && $item['isOrganic'] === 'Y' ? true : false,
                'label' => isset($item['labels']['large']) ? $item['labels']['large'] : null,
                'serving_temperature' => isset($item['serving_temperature']) ? $item['serving_temperature'] : null,
                'variation_from' => isset($item['variation_from']) ? $item['variation_from'] : null,
            ];
        }

        return $data;
    }

    public function getFields()
    {
        return [
            '_remoteId',
            '_creationDate',
            '_styleId',
            'name',
            'description',
            'abv',
            'ibu',
            'glass',
            'is_organic',
            'label',
            'serving_temperature',
            'variation_from'
        ];
    }
}
