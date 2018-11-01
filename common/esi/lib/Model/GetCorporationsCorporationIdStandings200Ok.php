<?php
/**
 * GetCorporationsCorporationIdStandings200Ok
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
 * GetCorporationsCorporationIdStandings200Ok Class Doc Comment
 *
 * @category    Class */
 // @description 200 ok object
/** 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class GetCorporationsCorporationIdStandings200Ok implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'get_corporations_corporation_id_standings_200_ok';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'from_id' => 'int',
        'from_type' => 'string',
        'standing' => 'float'
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
        'from_id' => 'from_id',
        'from_type' => 'from_type',
        'standing' => 'standing'
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
        'from_id' => 'setFromId',
        'from_type' => 'setFromType',
        'standing' => 'setStanding'
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
        'from_id' => 'getFromId',
        'from_type' => 'getFromType',
        'standing' => 'getStanding'
    );

    public static function getters()
    {
        return self::$getters;
    }

    const FROM_TYPE_AGENT = 'agent';
    const FROM_TYPE_NPC_CORP = 'npc_corp';
    const FROM_TYPE_FACTION = 'faction';
    

    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getFromTypeAllowableValues()
    {
        return [
            self::FROM_TYPE_AGENT,
            self::FROM_TYPE_NPC_CORP,
            self::FROM_TYPE_FACTION,
        ];
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
        $this->container['from_id'] = isset($data['from_id']) ? $data['from_id'] : null;
        $this->container['from_type'] = isset($data['from_type']) ? $data['from_type'] : null;
        $this->container['standing'] = isset($data['standing']) ? $data['standing'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();
        if ($this->container['from_id'] === null) {
            $invalid_properties[] = "'from_id' can't be null";
        }
        if ($this->container['from_type'] === null) {
            $invalid_properties[] = "'from_type' can't be null";
        }
        $allowed_values = array("agent", "npc_corp", "faction");
        if (!in_array($this->container['from_type'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'from_type', must be one of #{allowed_values}.";
        }

        if ($this->container['standing'] === null) {
            $invalid_properties[] = "'standing' can't be null";
        }
        if (($this->container['standing'] > 10.0)) {
            $invalid_properties[] = "invalid value for 'standing', must be smaller than or equal to 10.0.";
        }

        if (($this->container['standing'] < -10.0)) {
            $invalid_properties[] = "invalid value for 'standing', must be bigger than or equal to -10.0.";
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
        if ($this->container['from_id'] === null) {
            return false;
        }
        if ($this->container['from_type'] === null) {
            return false;
        }
        $allowed_values = array("agent", "npc_corp", "faction");
        if (!in_array($this->container['from_type'], $allowed_values)) {
            return false;
        }
        if ($this->container['standing'] === null) {
            return false;
        }
        if ($this->container['standing'] > 10.0) {
            return false;
        }
        if ($this->container['standing'] < -10.0) {
            return false;
        }
        return true;
    }


    /**
     * Gets from_id
     * @return int
     */
    public function getFromId()
    {
        return $this->container['from_id'];
    }

    /**
     * Sets from_id
     * @param int $from_id from_id integer
     * @return $this
     */
    public function setFromId($from_id)
    {
        $this->container['from_id'] = $from_id;

        return $this;
    }

    /**
     * Gets from_type
     * @return string
     */
    public function getFromType()
    {
        return $this->container['from_type'];
    }

    /**
     * Sets from_type
     * @param string $from_type from_type string
     * @return $this
     */
    public function setFromType($from_type)
    {
        $allowed_values = array('agent', 'npc_corp', 'faction');
        if (!in_array($from_type, $allowed_values)) {
            throw new \InvalidArgumentException("Invalid value for 'from_type', must be one of 'agent', 'npc_corp', 'faction'");
        }
        $this->container['from_type'] = $from_type;

        return $this;
    }

    /**
     * Gets standing
     * @return float
     */
    public function getStanding()
    {
        return $this->container['standing'];
    }

    /**
     * Sets standing
     * @param float $standing standing number
     * @return $this
     */
    public function setStanding($standing)
    {

        if ($standing > 10.0) {
            throw new \InvalidArgumentException('invalid value for $standing when calling GetCorporationsCorporationIdStandings200Ok., must be smaller than or equal to 10.0.');
        }
        if ($standing < -10.0) {
            throw new \InvalidArgumentException('invalid value for $standing when calling GetCorporationsCorporationIdStandings200Ok., must be bigger than or equal to -10.0.');
        }
        $this->container['standing'] = $standing;

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

