<?php
/**
 * GetFleetsFleetIdOk
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
 * OpenAPI spec version: 0.4.2.dev17
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
 * GetFleetsFleetIdOk Class Doc Comment
 *
 * @category    Class */
 // @description 200 ok object
/** 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class GetFleetsFleetIdOk implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'get_fleets_fleet_id_ok';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'is_free_move' => 'bool',
        'is_registered' => 'bool',
        'is_voice_enabled' => 'bool',
        'motd' => 'string'
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
        'is_free_move' => 'is_free_move',
        'is_registered' => 'is_registered',
        'is_voice_enabled' => 'is_voice_enabled',
        'motd' => 'motd'
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
        'is_free_move' => 'setIsFreeMove',
        'is_registered' => 'setIsRegistered',
        'is_voice_enabled' => 'setIsVoiceEnabled',
        'motd' => 'setMotd'
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
        'is_free_move' => 'getIsFreeMove',
        'is_registered' => 'getIsRegistered',
        'is_voice_enabled' => 'getIsVoiceEnabled',
        'motd' => 'getMotd'
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
        $this->container['is_free_move'] = isset($data['is_free_move']) ? $data['is_free_move'] : null;
        $this->container['is_registered'] = isset($data['is_registered']) ? $data['is_registered'] : null;
        $this->container['is_voice_enabled'] = isset($data['is_voice_enabled']) ? $data['is_voice_enabled'] : null;
        $this->container['motd'] = isset($data['motd']) ? $data['motd'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();
        if ($this->container['is_free_move'] === null) {
            $invalid_properties[] = "'is_free_move' can't be null";
        }
        if ($this->container['is_registered'] === null) {
            $invalid_properties[] = "'is_registered' can't be null";
        }
        if ($this->container['is_voice_enabled'] === null) {
            $invalid_properties[] = "'is_voice_enabled' can't be null";
        }
        if ($this->container['motd'] === null) {
            $invalid_properties[] = "'motd' can't be null";
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
        if ($this->container['is_free_move'] === null) {
            return false;
        }
        if ($this->container['is_registered'] === null) {
            return false;
        }
        if ($this->container['is_voice_enabled'] === null) {
            return false;
        }
        if ($this->container['motd'] === null) {
            return false;
        }
        return true;
    }


    /**
     * Gets is_free_move
     * @return bool
     */
    public function getIsFreeMove()
    {
        return $this->container['is_free_move'];
    }

    /**
     * Sets is_free_move
     * @param bool $is_free_move Is free-move enabled
     * @return $this
     */
    public function setIsFreeMove($is_free_move)
    {
        $this->container['is_free_move'] = $is_free_move;

        return $this;
    }

    /**
     * Gets is_registered
     * @return bool
     */
    public function getIsRegistered()
    {
        return $this->container['is_registered'];
    }

    /**
     * Sets is_registered
     * @param bool $is_registered Does the fleet have an active fleet advertisement
     * @return $this
     */
    public function setIsRegistered($is_registered)
    {
        $this->container['is_registered'] = $is_registered;

        return $this;
    }

    /**
     * Gets is_voice_enabled
     * @return bool
     */
    public function getIsVoiceEnabled()
    {
        return $this->container['is_voice_enabled'];
    }

    /**
     * Sets is_voice_enabled
     * @param bool $is_voice_enabled Is EVE Voice enabled
     * @return $this
     */
    public function setIsVoiceEnabled($is_voice_enabled)
    {
        $this->container['is_voice_enabled'] = $is_voice_enabled;

        return $this;
    }

    /**
     * Gets motd
     * @return string
     */
    public function getMotd()
    {
        return $this->container['motd'];
    }

    /**
     * Sets motd
     * @param string $motd Fleet MOTD in CCP flavoured HTML
     * @return $this
     */
    public function setMotd($motd)
    {
        $this->container['motd'] = $motd;

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


