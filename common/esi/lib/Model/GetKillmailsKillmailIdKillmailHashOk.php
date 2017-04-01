<?php
/**
 * GetKillmailsKillmailIdKillmailHashOk
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
 * GetKillmailsKillmailIdKillmailHashOk Class Doc Comment
 *
 * @category    Class */
 // @description 200 ok object
/** 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class GetKillmailsKillmailIdKillmailHashOk implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'get_killmails_killmail_id_killmail_hash_ok';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'attackers' => '\Swagger\Client\Model\GetKillmailsKillmailIdKillmailHashOkAttackers[]',
        'killmail_id' => 'int',
        'killmail_time' => '\DateTime',
        'moon_id' => 'int',
        'solar_system_id' => 'int',
        'victim' => '\Swagger\Client\Model\GetKillmailsKillmailIdKillmailHashOkVictim',
        'war_id' => 'int'
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
        'attackers' => 'attackers',
        'killmail_id' => 'killmail_id',
        'killmail_time' => 'killmail_time',
        'moon_id' => 'moon_id',
        'solar_system_id' => 'solar_system_id',
        'victim' => 'victim',
        'war_id' => 'war_id'
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
        'attackers' => 'setAttackers',
        'killmail_id' => 'setKillmailId',
        'killmail_time' => 'setKillmailTime',
        'moon_id' => 'setMoonId',
        'solar_system_id' => 'setSolarSystemId',
        'victim' => 'setVictim',
        'war_id' => 'setWarId'
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
        'attackers' => 'getAttackers',
        'killmail_id' => 'getKillmailId',
        'killmail_time' => 'getKillmailTime',
        'moon_id' => 'getMoonId',
        'solar_system_id' => 'getSolarSystemId',
        'victim' => 'getVictim',
        'war_id' => 'getWarId'
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
        $this->container['attackers'] = isset($data['attackers']) ? $data['attackers'] : null;
        $this->container['killmail_id'] = isset($data['killmail_id']) ? $data['killmail_id'] : null;
        $this->container['killmail_time'] = isset($data['killmail_time']) ? $data['killmail_time'] : null;
        $this->container['moon_id'] = isset($data['moon_id']) ? $data['moon_id'] : null;
        $this->container['solar_system_id'] = isset($data['solar_system_id']) ? $data['solar_system_id'] : null;
        $this->container['victim'] = isset($data['victim']) ? $data['victim'] : null;
        $this->container['war_id'] = isset($data['war_id']) ? $data['war_id'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();
        if ($this->container['attackers'] === null) {
            $invalid_properties[] = "'attackers' can't be null";
        }
        if ($this->container['killmail_id'] === null) {
            $invalid_properties[] = "'killmail_id' can't be null";
        }
        if ($this->container['killmail_time'] === null) {
            $invalid_properties[] = "'killmail_time' can't be null";
        }
        if ($this->container['solar_system_id'] === null) {
            $invalid_properties[] = "'solar_system_id' can't be null";
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
        if ($this->container['attackers'] === null) {
            return false;
        }
        if ($this->container['killmail_id'] === null) {
            return false;
        }
        if ($this->container['killmail_time'] === null) {
            return false;
        }
        if ($this->container['solar_system_id'] === null) {
            return false;
        }
        return true;
    }


    /**
     * Gets attackers
     * @return \Swagger\Client\Model\GetKillmailsKillmailIdKillmailHashOkAttackers[]
     */
    public function getAttackers()
    {
        return $this->container['attackers'];
    }

    /**
     * Sets attackers
     * @param \Swagger\Client\Model\GetKillmailsKillmailIdKillmailHashOkAttackers[] $attackers attackers array
     * @return $this
     */
    public function setAttackers($attackers)
    {
        $this->container['attackers'] = $attackers;

        return $this;
    }

    /**
     * Gets killmail_id
     * @return int
     */
    public function getKillmailId()
    {
        return $this->container['killmail_id'];
    }

    /**
     * Sets killmail_id
     * @param int $killmail_id ID of the killmail
     * @return $this
     */
    public function setKillmailId($killmail_id)
    {
        $this->container['killmail_id'] = $killmail_id;

        return $this;
    }

    /**
     * Gets killmail_time
     * @return \DateTime
     */
    public function getKillmailTime()
    {
        return $this->container['killmail_time'];
    }

    /**
     * Sets killmail_time
     * @param \DateTime $killmail_time Time that the victim was killed and the killmail generated
     * @return $this
     */
    public function setKillmailTime($killmail_time)
    {
        $this->container['killmail_time'] = $killmail_time;

        return $this;
    }

    /**
     * Gets moon_id
     * @return int
     */
    public function getMoonId()
    {
        return $this->container['moon_id'];
    }

    /**
     * Sets moon_id
     * @param int $moon_id Moon if the kill took place at one
     * @return $this
     */
    public function setMoonId($moon_id)
    {
        $this->container['moon_id'] = $moon_id;

        return $this;
    }

    /**
     * Gets solar_system_id
     * @return int
     */
    public function getSolarSystemId()
    {
        return $this->container['solar_system_id'];
    }

    /**
     * Sets solar_system_id
     * @param int $solar_system_id Solar system that the kill took place in
     * @return $this
     */
    public function setSolarSystemId($solar_system_id)
    {
        $this->container['solar_system_id'] = $solar_system_id;

        return $this;
    }

    /**
     * Gets victim
     * @return \Swagger\Client\Model\GetKillmailsKillmailIdKillmailHashOkVictim
     */
    public function getVictim()
    {
        return $this->container['victim'];
    }

    /**
     * Sets victim
     * @param \Swagger\Client\Model\GetKillmailsKillmailIdKillmailHashOkVictim $victim
     * @return $this
     */
    public function setVictim($victim)
    {
        $this->container['victim'] = $victim;

        return $this;
    }

    /**
     * Gets war_id
     * @return int
     */
    public function getWarId()
    {
        return $this->container['war_id'];
    }

    /**
     * Sets war_id
     * @param int $war_id War if the killmail is generated in relation to an official war
     * @return $this
     */
    public function setWarId($war_id)
    {
        $this->container['war_id'] = $war_id;

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


