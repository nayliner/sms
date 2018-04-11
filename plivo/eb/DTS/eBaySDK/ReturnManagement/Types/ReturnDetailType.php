<?php
/**
 * The contents of this file was generated using the WSDLs as provided by eBay.
 *
 * DO NOT EDIT THIS FILE!
 */

namespace DTS\eBaySDK\ReturnManagement\Types;

/**
 *
 * @property string $globalId
 * @property \DTS\eBaySDK\ReturnManagement\Types\ReturnResponseHistoryType[] $returnHistory
 * @property \DTS\eBaySDK\ReturnManagement\Types\RefundInfoType $refundInfo
 * @property \DTS\eBaySDK\ReturnManagement\Types\ReturnPolicyInfoType $returnPolicy
 * @property \DTS\eBaySDK\ReturnManagement\Types\ShipmentType $buyerReturnShipment
 * @property \DTS\eBaySDK\ReturnManagement\Types\ShipmentType[] $shipmentInfo
 * @property \DTS\eBaySDK\ReturnManagement\Types\CaseIdType $caseId
 */
class ReturnDetailType extends \DTS\eBaySDK\Types\BaseType
{
    /**
     * @var array Properties belonging to objects of this class.
     */
    private static $propertyTypes = [
        'globalId' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'globalId'
        ],
        'returnHistory' => [
            'type' => 'DTS\eBaySDK\ReturnManagement\Types\ReturnResponseHistoryType',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'returnHistory'
        ],
        'refundInfo' => [
            'type' => 'DTS\eBaySDK\ReturnManagement\Types\RefundInfoType',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'refundInfo'
        ],
        'returnPolicy' => [
            'type' => 'DTS\eBaySDK\ReturnManagement\Types\ReturnPolicyInfoType',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'returnPolicy'
        ],
        'buyerReturnShipment' => [
            'type' => 'DTS\eBaySDK\ReturnManagement\Types\ShipmentType',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'buyerReturnShipment'
        ],
        'shipmentInfo' => [
            'type' => 'DTS\eBaySDK\ReturnManagement\Types\ShipmentType',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'shipmentInfo'
        ],
        'caseId' => [
            'type' => 'DTS\eBaySDK\ReturnManagement\Types\CaseIdType',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'caseId'
        ]
    ];

    /**
     * @param array $values Optional properties and values to assign to the object.
     */
    public function __construct(array $values = [])
    {
        list($parentValues, $childValues) = self::getParentValues(self::$propertyTypes, $values);

        parent::__construct($parentValues);

        if (!array_key_exists(__CLASS__, self::$properties)) {
            self::$properties[__CLASS__] = array_merge(self::$properties[get_parent_class()], self::$propertyTypes);
        }

        if (!array_key_exists(__CLASS__, self::$xmlNamespaces)) {
            self::$xmlNamespaces[__CLASS__] = 'xmlns="http://www.ebay.com/marketplace/returns/v1/services"';
        }

        $this->setValues(__CLASS__, $childValues);
    }
}