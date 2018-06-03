<?php
/**
 * GetAlliancesAllianceIdOk
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
 * GetAlliancesAllianceIdOk Class Doc Comment
 *
 * @category    Class */
 // @description 200 ok object
/** 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class GetAlliancesAllianceIdOk implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'get_alliances_alliance_id_ok';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'creator_corporation_id' => 'int',
        'creator_id' => 'int',
        'date_founded' => '\DateTime',
        'executor_corporation_id' => 'int',
        'faction_id' => 'int',
        'name' => 'string',
        'ticker' => 'string'
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
        'creator_corporation_id' => 'creator_corporation_id',
        'creator_id' => 'creator_id',
        'date_founded' => 'date_founded',
        'executor_corporation_id' => 'executor_corporation_id',
        'faction_id' => 'faction_id',
        'name' => 'name',
        'ticker' => 'ticker'
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
        'creator_corporation_id' => 'setCreatorCorporationId',
        'creator_id' => 'setCreatorId',
        'date_founded' => 'setDateFounded',
        'executor_corporation_id' => 'setExecutorCorporationId',
        'faction_id' => 'setFactionId',
        'name' => 'setName',
        'ticker' => 'setTicker'
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
        'creator_corporation_id' => 'getCreatorCorporationId',
        'creator_id' => 'getCreatorId',
        'date_founded' => 'getDateFounded',
        'executor_corporation_id' => 'getExecutorCorporationId',
        'faction_id' => 'getFactionId',
        'name' => 'getName',
        'ticker' => 'getTicker'
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
        $this->container['creator_corporation_id'] = isset($data['creator_corporation_id']) ? $data['creator_corporation_id'] : null;
        $this->container['creator_id'] = isset($data['creator_id']) ? $data['creator_id'] : null;
        $this->container['date_founded'] = isset($data['date_founded']) ? $data['date_founded'] : null;
        $this->container['executor_corporation_id'] = isset($data['executor_corporation_id']) ? $data['executor_corporation_id'] : null;
        $this->container['faction_id'] = isset($data['faction_id']) ? $data['faction_id'] : null;
        $this->container['name'] = isset($data['name']) ? $data['name'] : null;
        $this->container['ticker'] = isset($data['ticker']) ? $data['ticker'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();
        if ($this->container['creator_corporation_id'] === null) {
            $invalid_properties[] = "'creator_corporation_id' can't be null";
        }
        if ($this->container['creator_id'] === null) {
            $invalid_properties[] = "'creator_id' can't be null";
        }
        if ($this->container['date_founded'] === null) {
            $invalid_properties[] = "'date_founded' can't be null";
        }
        if ($this->container['name'] === null) {
            $invalid_properties[] = "'name' can't be null";
        }
        if ($this->container['ticker'] === null) {
            $invalid_properties[] = "'ticker' can't be null";
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
        if ($this->container['creator_corporation_id'] === null) {
            return false;
        }
        if ($this->container['creator_id'] === null) {
            return false;
        }
        if ($this->container['date_founded'] === null) {
            return false;
        }
        if ($this->container['name'] === null) {
            return false;
        }
        if ($this->container['ticker'] === null) {
            return false;
        }
        return true;
    }


    /**
     * Gets creator_corporation_id
     * @return int
     */
    public function getCreatorCorporationId()
    {
        return $this->container['creator_corporation_id'];
    }

    /**
     * Sets creator_corporation_id
     * @param int $creator_corporation_id ID of the corporation that created the alliance
     * @return $this
     */
    public function setCreatorCorporationId($creator_corporation_id)
    {
        $this->container['creator_corporation_id'] = $creator_corporation_id;

        return $this;
    }

    /**
     * Gets creator_id
     * @return int
     */
    public function getCreatorId()
    {
        return $this->container['creator_id'];
    }

    /**
     * Sets creator_id
     * @param int $creator_id ID of the character that created the alliance
     * @return $this
     */
    public function setCreatorId($creator_id)
    {
        $this->container['creator_id'] = $creator_id;

        return $this;
    }

    /**
     * Gets date_founded
     * @return \DateTime
     */
    public function getDateFounded()
    {
        return $this->container['date_founded'];
    }

    /**
     * Sets date_founded
     * @param \DateTime $date_founded date_founded string
     * @return $this
     */
    public function setDateFounded($date_founded)
    {
        $this->container['date_founded'] = $date_founded;

        return $this;
    }

    /**
     * Gets executor_corporation_id
     * @return int
     */
    public function getExecutorCorporationId()
    {
        return $this->container['executor_corporation_id'];
    }

    /**
     * Sets executor_corporation_id
     * @param int $executor_corporation_id the executor corporation ID, if this alliance is not closed
     * @return $this
     */
    public function setExecutorCorporationId($executor_corporation_id)
    {
        $this->container['executor_corporation_id'] = $executor_corporation_id;

        return $this;
    }

    /**
     * Gets faction_id
     * @return int
     */
    public function getFactionId()
    {
        return $this->container['faction_id'];
    }

    /**
     * Sets faction_id
     * @param int $faction_id Faction ID this alliance is fighting for, if this alliance is enlisted in factional warfare
     * @return $this
     */
    public function setFactionId($faction_id)
    {
        $this->container['faction_id'] = $faction_id;

        return $this;
    }

    /**
     * Gets name
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     * @param string $name the full name of the alliance
     * @return $this
     */
    public function setName($name)
    {
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets ticker
     * @return string
     */
    public function getTicker()
    {
        return $this->container['ticker'];
    }

    /**
     * Sets ticker
     * @param string $ticker the short name of the alliance
     * @return $this
     */
    public function setTicker($ticker)
    {
        $this->container['ticker'] = $ticker;

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


