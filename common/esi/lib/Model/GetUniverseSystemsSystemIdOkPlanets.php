<?php
/**
 * GetUniverseSystemsSystemIdOkPlanets
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
 * GetUniverseSystemsSystemIdOkPlanets Class Doc Comment
 *
 * @category    Class */
 // @description planet object
/** 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class GetUniverseSystemsSystemIdOkPlanets implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'get_universe_systems_system_id_ok_planets';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'asteroid_belts' => 'int[]',
        'moons' => 'int[]',
        'planet_id' => 'int'
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
        'asteroid_belts' => 'asteroid_belts',
        'moons' => 'moons',
        'planet_id' => 'planet_id'
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
        'asteroid_belts' => 'setAsteroidBelts',
        'moons' => 'setMoons',
        'planet_id' => 'setPlanetId'
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
        'asteroid_belts' => 'getAsteroidBelts',
        'moons' => 'getMoons',
        'planet_id' => 'getPlanetId'
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
        $this->container['asteroid_belts'] = isset($data['asteroid_belts']) ? $data['asteroid_belts'] : null;
        $this->container['moons'] = isset($data['moons']) ? $data['moons'] : null;
        $this->container['planet_id'] = isset($data['planet_id']) ? $data['planet_id'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();
        if ($this->container['planet_id'] === null) {
            $invalid_properties[] = "'planet_id' can't be null";
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
        if ($this->container['planet_id'] === null) {
            return false;
        }
        return true;
    }


    /**
     * Gets asteroid_belts
     * @return int[]
     */
    public function getAsteroidBelts()
    {
        return $this->container['asteroid_belts'];
    }

    /**
     * Sets asteroid_belts
     * @param int[] $asteroid_belts asteroid_belts array
     * @return $this
     */
    public function setAsteroidBelts($asteroid_belts)
    {
        $this->container['asteroid_belts'] = $asteroid_belts;

        return $this;
    }

    /**
     * Gets moons
     * @return int[]
     */
    public function getMoons()
    {
        return $this->container['moons'];
    }

    /**
     * Sets moons
     * @param int[] $moons moons array
     * @return $this
     */
    public function setMoons($moons)
    {
        $this->container['moons'] = $moons;

        return $this;
    }

    /**
     * Gets planet_id
     * @return int
     */
    public function getPlanetId()
    {
        return $this->container['planet_id'];
    }

    /**
     * Sets planet_id
     * @param int $planet_id planet_id integer
     * @return $this
     */
    public function setPlanetId($planet_id)
    {
        $this->container['planet_id'] = $planet_id;

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


