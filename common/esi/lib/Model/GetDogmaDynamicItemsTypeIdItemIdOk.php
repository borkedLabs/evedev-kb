<?php
/**
 * GetDogmaDynamicItemsTypeIdItemIdOk
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * EVE Swagger Interface
 *
 * An OpenAPI for EVE Online
 *
 * OpenAPI spec version: 0.8.3
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\Model;

use \ArrayAccess;

/**
 * GetDogmaDynamicItemsTypeIdItemIdOk Class Doc Comment
 *
 * @category    Class */
 // @description 200 ok object
/** 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class GetDogmaDynamicItemsTypeIdItemIdOk implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'get_dogma_dynamic_items_type_id_item_id_ok';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'created_by' => 'int',
        'dogma_attributes' => '\Swagger\Client\Model\GetDogmaDynamicItemsTypeIdItemIdOkDogmaAttributes[]',
        'dogma_effects' => '\Swagger\Client\Model\GetDogmaDynamicItemsTypeIdItemIdOkDogmaEffects[]',
        'mutator_type_id' => 'int',
        'source_type_id' => 'int'
    );

    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = array(
        'created_by' => 'created_by',
        'dogma_attributes' => 'dogma_attributes',
        'dogma_effects' => 'dogma_effects',
        'mutator_type_id' => 'mutator_type_id',
        'source_type_id' => 'source_type_id'
    );

    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = array(
        'created_by' => 'setCreatedBy',
        'dogma_attributes' => 'setDogmaAttributes',
        'dogma_effects' => 'setDogmaEffects',
        'mutator_type_id' => 'setMutatorTypeId',
        'source_type_id' => 'setSourceTypeId'
    );

    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = array(
        'created_by' => 'getCreatedBy',
        'dogma_attributes' => 'getDogmaAttributes',
        'dogma_effects' => 'getDogmaEffects',
        'mutator_type_id' => 'getMutatorTypeId',
        'source_type_id' => 'getSourceTypeId'
    );

    public static function getters()
    {
        return self::$getters;
    }

    

    

    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = array();

    /**
     * Constructor
     * @param mixed[] $data Associated array of property value initalizing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['created_by'] = isset($data['created_by']) ? $data['created_by'] : null;
        $this->container['dogma_attributes'] = isset($data['dogma_attributes']) ? $data['dogma_attributes'] : null;
        $this->container['dogma_effects'] = isset($data['dogma_effects']) ? $data['dogma_effects'] : null;
        $this->container['mutator_type_id'] = isset($data['mutator_type_id']) ? $data['mutator_type_id'] : null;
        $this->container['source_type_id'] = isset($data['source_type_id']) ? $data['source_type_id'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();
        if ($this->container['created_by'] === null) {
            $invalid_properties[] = "'created_by' can't be null";
        }
        if ($this->container['dogma_attributes'] === null) {
            $invalid_properties[] = "'dogma_attributes' can't be null";
        }
        if ($this->container['dogma_effects'] === null) {
            $invalid_properties[] = "'dogma_effects' can't be null";
        }
        if ($this->container['mutator_type_id'] === null) {
            $invalid_properties[] = "'mutator_type_id' can't be null";
        }
        if ($this->container['source_type_id'] === null) {
            $invalid_properties[] = "'source_type_id' can't be null";
        }
        return $invalid_properties;
    }

    /**
     * validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properteis are valid
     */
    public function valid()
    {
        if ($this->container['created_by'] === null) {
            return false;
        }
        if ($this->container['dogma_attributes'] === null) {
            return false;
        }
        if ($this->container['dogma_effects'] === null) {
            return false;
        }
        if ($this->container['mutator_type_id'] === null) {
            return false;
        }
        if ($this->container['source_type_id'] === null) {
            return false;
        }
        return true;
    }


    /**
     * Gets created_by
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->container['created_by'];
    }

    /**
     * Sets created_by
     * @param int $created_by The ID of the character who created the item
     * @return $this
     */
    public function setCreatedBy($created_by)
    {
        $this->container['created_by'] = $created_by;

        return $this;
    }

    /**
     * Gets dogma_attributes
     * @return \Swagger\Client\Model\GetDogmaDynamicItemsTypeIdItemIdOkDogmaAttributes[]
     */
    public function getDogmaAttributes()
    {
        return $this->container['dogma_attributes'];
    }

    /**
     * Sets dogma_attributes
     * @param \Swagger\Client\Model\GetDogmaDynamicItemsTypeIdItemIdOkDogmaAttributes[] $dogma_attributes dogma_attributes array
     * @return $this
     */
    public function setDogmaAttributes($dogma_attributes)
    {
        $this->container['dogma_attributes'] = $dogma_attributes;

        return $this;
    }

    /**
     * Gets dogma_effects
     * @return \Swagger\Client\Model\GetDogmaDynamicItemsTypeIdItemIdOkDogmaEffects[]
     */
    public function getDogmaEffects()
    {
        return $this->container['dogma_effects'];
    }

    /**
     * Sets dogma_effects
     * @param \Swagger\Client\Model\GetDogmaDynamicItemsTypeIdItemIdOkDogmaEffects[] $dogma_effects dogma_effects array
     * @return $this
     */
    public function setDogmaEffects($dogma_effects)
    {
        $this->container['dogma_effects'] = $dogma_effects;

        return $this;
    }

    /**
     * Gets mutator_type_id
     * @return int
     */
    public function getMutatorTypeId()
    {
        return $this->container['mutator_type_id'];
    }

    /**
     * Sets mutator_type_id
     * @param int $mutator_type_id The type ID of the mutator used to generate the dynamic item.
     * @return $this
     */
    public function setMutatorTypeId($mutator_type_id)
    {
        $this->container['mutator_type_id'] = $mutator_type_id;

        return $this;
    }

    /**
     * Gets source_type_id
     * @return int
     */
    public function getSourceTypeId()
    {
        return $this->container['source_type_id'];
    }

    /**
     * Sets source_type_id
     * @param int $source_type_id The type ID of the source item the mutator was applied to create the dynamic item.
     * @return $this
     */
    public function setSourceTypeId($source_type_id)
    {
        $this->container['source_type_id'] = $source_type_id;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param  integer $offset Offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     * @param  integer $offset Offset
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     * @param  integer $offset Offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this));
    }
}


