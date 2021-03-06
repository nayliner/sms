<?php
/**
 * The contents of this file was generated using the WSDLs as provided by eBay.
 *
 * DO NOT EDIT THIS FILE!
 */

namespace DTS\eBaySDK\Trading\Types;

/**
 *
 * @property \DTS\eBaySDK\Trading\Types\MyMessagesFolderSummaryType[] $FolderSummary
 * @property integer $NewAlertCount
 * @property integer $NewMessageCount
 * @property integer $UnresolvedAlertCount
 * @property integer $FlaggedMessageCount
 * @property integer $TotalAlertCount
 * @property integer $TotalMessageCount
 * @property integer $NewHighPriorityCount
 * @property integer $TotalHighPriorityCount
 */
class MyMessagesSummaryType extends \DTS\eBaySDK\Types\BaseType
{
    /**
     * @var array Properties belonging to objects of this class.
     */
    private static $propertyTypes = [
        'FolderSummary' => [
            'type' => 'DTS\eBaySDK\Trading\Types\MyMessagesFolderSummaryType',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'FolderSummary'
        ],
        'NewAlertCount' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'NewAlertCount'
        ],
        'NewMessageCount' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'NewMessageCount'
        ],
        'UnresolvedAlertCount' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'UnresolvedAlertCount'
        ],
        'FlaggedMessageCount' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'FlaggedMessageCount'
        ],
        'TotalAlertCount' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'TotalAlertCount'
        ],
        'TotalMessageCount' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'TotalMessageCount'
        ],
        'NewHighPriorityCount' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'NewHighPriorityCount'
        ],
        'TotalHighPriorityCount' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'TotalHighPriorityCount'
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
            self::$xmlNamespaces[__CLASS__] = 'xmlns="urn:ebay:apis:eBLBaseComponents"';
        }

        $this->setValues(__CLASS__, $childValues);
    }
}
