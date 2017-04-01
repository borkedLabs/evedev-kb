<?php
/**
 * GetCorporationsCorporationIdOk
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
 * GetCorporationsCorporationIdOk Class Doc Comment
 *
 * @category    Class */
 // @description 200 ok object
/** 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class GetCorporationsCorporationIdOk implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'get_corporations_corporation_id_ok';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'alliance_id' => 'int',
        'ceo_id' => 'int',
        'corporation_description' => 'string',
        'corporation_name' => 'string',
        'creation_date' => '\DateTime',
        'creator_id' => 'int',
        'faction' => 'string',
        'member_count' => 'int',
        'tax_rate' => 'float',
        'ticker' => 'string',
        'url' => 'string'
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
        'alliance_id' => 'alliance_id',
        'ceo_id' => 'ceo_id',
        'corporation_description' => 'corporation_description',
        'corporation_name' => 'corporation_name',
        'creation_date' => 'creation_date',
        'creator_id' => 'creator_id',
        'faction' => 'faction',
        'member_count' => 'member_count',
        'tax_rate' => 'tax_rate',
        'ticker' => 'ticker',
        'url' => 'url'
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
        'alliance_id' => 'setAllianceId',
        'ceo_id' => 'setCeoId',
        'corporation_description' => 'setCorporationDescription',
        'corporation_name' => 'setCorporationName',
        'creation_date' => 'setCreationDate',
        'creator_id' => 'setCreatorId',
        'faction' => 'setFaction',
        'member_count' => 'setMemberCount',
        'tax_rate' => 'setTaxRate',
        'ticker' => 'setTicker',
        'url' => 'setUrl'
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
        'alliance_id' => 'getAllianceId',
        'ceo_id' => 'getCeoId',
        'corporation_description' => 'getCorporationDescription',
        'corporation_name' => 'getCorporationName',
        'creation_date' => 'getCreationDate',
        'creator_id' => 'getCreatorId',
        'faction' => 'getFaction',
        'member_count' => 'getMemberCount',
        'tax_rate' => 'getTaxRate',
        'ticker' => 'getTicker',
        'url' => 'getUrl'
    );

    public static function getters()
    {
        return self::$getters;
    }

    const FACTION_MINMATAR = 'Minmatar';
    const FACTION_GALLENTE = 'Gallente';
    const FACTION_CALDARI = 'Caldari';
    const FACTION_AMARR = 'Amarr';
    

    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getFactionAllowableValues()
    {
        return [
            self::FACTION_MINMATAR,
            self::FACTION_GALLENTE,
            self::FACTION_CALDARI,
            self::FACTION_AMARR,
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
        $this->container['alliance_id'] = isset($data['alliance_id']) ? $data['alliance_id'] : null;
        $this->container['ceo_id'] = isset($data['ceo_id']) ? $data['ceo_id'] : null;
        $this->container['corporation_description'] = isset($data['corporation_description']) ? $data['corporation_description'] : null;
        $this->container['corporation_name'] = isset($data['corporation_name']) ? $data['corporation_name'] : null;
        $this->container['creation_date'] = isset($data['creation_date']) ? $data['creation_date'] : null;
        $this->container['creator_id'] = isset($data['creator_id']) ? $data['creator_id'] : null;
        $this->container['faction'] = isset($data['faction']) ? $data['faction'] : null;
        $this->container['member_count'] = isset($data['member_count']) ? $data['member_count'] : null;
        $this->container['tax_rate'] = isset($data['tax_rate']) ? $data['tax_rate'] : null;
        $this->container['ticker'] = isset($data['ticker']) ? $data['ticker'] : null;
        $this->container['url'] = isset($data['url']) ? $data['url'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();
        if ($this->container['ceo_id'] === null) {
            $invalid_properties[] = "'ceo_id' can't be null";
        }
        if ($this->container['corporation_description'] === null) {
            $invalid_properties[] = "'corporation_description' can't be null";
        }
        if ($this->container['corporation_name'] === null) {
            $invalid_properties[] = "'corporation_name' can't be null";
        }
        if ($this->container['creator_id'] === null) {
            $invalid_properties[] = "'creator_id' can't be null";
        }
        $allowed_values = array("Minmatar", "Gallente", "Caldari", "Amarr");
        if (!in_array($this->container['faction'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'faction', must be one of #{allowed_values}.";
        }

        if ($this->container['member_count'] === null) {
            $invalid_properties[] = "'member_count' can't be null";
        }
        if ($this->container['tax_rate'] === null) {
            $invalid_properties[] = "'tax_rate' can't be null";
        }
        if (($this->container['tax_rate'] > 1.0)) {
            $invalid_properties[] = "invalid value for 'tax_rate', must be smaller than or equal to 1.0.";
        }

        if (($this->container['tax_rate'] < 0.0)) {
            $invalid_properties[] = "invalid value for 'tax_rate', must be bigger than or equal to 0.0.";
        }

        if ($this->container['ticker'] === null) {
            $invalid_properties[] = "'ticker' can't be null";
        }
        if ($this->container['url'] === null) {
            $invalid_properties[] = "'url' can't be null";
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
        if ($this->container['ceo_id'] === null) {
            return false;
        }
        if ($this->container['corporation_description'] === null) {
            return false;
        }
        if ($this->container['corporation_name'] === null) {
            return false;
        }
        if ($this->container['creator_id'] === null) {
            return false;
        }
        $allowed_values = array("Minmatar", "Gallente", "Caldari", "Amarr");
        if (!in_array($this->container['faction'], $allowed_values)) {
            return false;
        }
        if ($this->container['member_count'] === null) {
            return false;
        }
        if ($this->container['tax_rate'] === null) {
            return false;
        }
        if ($this->container['tax_rate'] > 1.0) {
            return false;
        }
        if ($this->container['tax_rate'] < 0.0) {
            return false;
        }
        if ($this->container['ticker'] === null) {
            return false;
        }
        if ($this->container['url'] === null) {
            return false;
        }
        return true;
    }


    /**
     * Gets alliance_id
     * @return int
     */
    public function getAllianceId()
    {
        return $this->container['alliance_id'];
    }

    /**
     * Sets alliance_id
     * @param int $alliance_id id of alliance that corporation is a member of, if any
     * @return $this
     */
    public function setAllianceId($alliance_id)
    {
        $this->container['alliance_id'] = $alliance_id;

        return $this;
    }

    /**
     * Gets ceo_id
     * @return int
     */
    public function getCeoId()
    {
        return $this->container['ceo_id'];
    }

    /**
     * Sets ceo_id
     * @param int $ceo_id ceo_id integer
     * @return $this
     */
    public function setCeoId($ceo_id)
    {
        $this->container['ceo_id'] = $ceo_id;

        return $this;
    }

    /**
     * Gets corporation_description
     * @return string
     */
    public function getCorporationDescription()
    {
        return $this->container['corporation_description'];
    }

    /**
     * Sets corporation_description
     * @param string $corporation_description corporation_description string
     * @return $this
     */
    public function setCorporationDescription($corporation_description)
    {
        $this->container['corporation_description'] = $corporation_description;

        return $this;
    }

    /**
     * Gets corporation_name
     * @return string
     */
    public function getCorporationName()
    {
        return $this->container['corporation_name'];
    }

    /**
     * Sets corporation_name
     * @param string $corporation_name the full name of the corporation
     * @return $this
     */
    public function setCorporationName($corporation_name)
    {
        $this->container['corporation_name'] = $corporation_name;

        return $this;
    }

    /**
     * Gets creation_date
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->container['creation_date'];
    }

    /**
     * Sets creation_date
     * @param \DateTime $creation_date creation_date string
     * @return $this
     */
    public function setCreationDate($creation_date)
    {
        $this->container['creation_date'] = $creation_date;

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
     * @param int $creator_id creator_id integer
     * @return $this
     */
    public function setCreatorId($creator_id)
    {
        $this->container['creator_id'] = $creator_id;

        return $this;
    }

    /**
     * Gets faction
     * @return string
     */
    public function getFaction()
    {
        return $this->container['faction'];
    }

    /**
     * Sets faction
     * @param string $faction faction string
     * @return $this
     */
    public function setFaction($faction)
    {
        $allowed_values = array('Minmatar', 'Gallente', 'Caldari', 'Amarr');
        if (!in_array($faction, $allowed_values)) {
            throw new \InvalidArgumentException("Invalid value for 'faction', must be one of 'Minmatar', 'Gallente', 'Caldari', 'Amarr'");
        }
        $this->container['faction'] = $faction;

        return $this;
    }

    /**
     * Gets member_count
     * @return int
     */
    public function getMemberCount()
    {
        return $this->container['member_count'];
    }

    /**
     * Sets member_count
     * @param int $member_count member_count integer
     * @return $this
     */
    public function setMemberCount($member_count)
    {
        $this->container['member_count'] = $member_count;

        return $this;
    }

    /**
     * Gets tax_rate
     * @return float
     */
    public function getTaxRate()
    {
        return $this->container['tax_rate'];
    }

    /**
     * Sets tax_rate
     * @param float $tax_rate tax_rate number
     * @return $this
     */
    public function setTaxRate($tax_rate)
    {

        if ($tax_rate > 1.0) {
            throw new \InvalidArgumentException('invalid value for $tax_rate when calling GetCorporationsCorporationIdOk., must be smaller than or equal to 1.0.');
        }
        if ($tax_rate < 0.0) {
            throw new \InvalidArgumentException('invalid value for $tax_rate when calling GetCorporationsCorporationIdOk., must be bigger than or equal to 0.0.');
        }
        $this->container['tax_rate'] = $tax_rate;

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
     * @param string $ticker the short name of the corporation
     * @return $this
     */
    public function setTicker($ticker)
    {
        $this->container['ticker'] = $ticker;

        return $this;
    }

    /**
     * Gets url
     * @return string
     */
    public function getUrl()
    {
        return $this->container['url'];
    }

    /**
     * Sets url
     * @param string $url url string
     * @return $this
     */
    public function setUrl($url)
    {
        $this->container['url'] = $url;

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


