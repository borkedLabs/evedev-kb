<?php
/**
 * V2characterscharacterIdstatsMining
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
 * V2characterscharacterIdstatsMining Class Doc Comment
 *
 * @category    Class */
 // @description mining object
/** 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class V2characterscharacterIdstatsMining implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'v2characterscharacter_idstats_mining';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'drone_mine' => 'int',
        'ore_arkonor' => 'int',
        'ore_bistot' => 'int',
        'ore_crokite' => 'int',
        'ore_dark_ochre' => 'int',
        'ore_gneiss' => 'int',
        'ore_harvestable_cloud' => 'int',
        'ore_hedbergite' => 'int',
        'ore_hemorphite' => 'int',
        'ore_ice' => 'int',
        'ore_jaspet' => 'int',
        'ore_kernite' => 'int',
        'ore_mercoxit' => 'int',
        'ore_omber' => 'int',
        'ore_plagioclase' => 'int',
        'ore_pyroxeres' => 'int',
        'ore_scordite' => 'int',
        'ore_spodumain' => 'int',
        'ore_veldspar' => 'int'
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
        'drone_mine' => 'drone_mine',
        'ore_arkonor' => 'ore_arkonor',
        'ore_bistot' => 'ore_bistot',
        'ore_crokite' => 'ore_crokite',
        'ore_dark_ochre' => 'ore_dark_ochre',
        'ore_gneiss' => 'ore_gneiss',
        'ore_harvestable_cloud' => 'ore_harvestable_cloud',
        'ore_hedbergite' => 'ore_hedbergite',
        'ore_hemorphite' => 'ore_hemorphite',
        'ore_ice' => 'ore_ice',
        'ore_jaspet' => 'ore_jaspet',
        'ore_kernite' => 'ore_kernite',
        'ore_mercoxit' => 'ore_mercoxit',
        'ore_omber' => 'ore_omber',
        'ore_plagioclase' => 'ore_plagioclase',
        'ore_pyroxeres' => 'ore_pyroxeres',
        'ore_scordite' => 'ore_scordite',
        'ore_spodumain' => 'ore_spodumain',
        'ore_veldspar' => 'ore_veldspar'
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
        'drone_mine' => 'setDroneMine',
        'ore_arkonor' => 'setOreArkonor',
        'ore_bistot' => 'setOreBistot',
        'ore_crokite' => 'setOreCrokite',
        'ore_dark_ochre' => 'setOreDarkOchre',
        'ore_gneiss' => 'setOreGneiss',
        'ore_harvestable_cloud' => 'setOreHarvestableCloud',
        'ore_hedbergite' => 'setOreHedbergite',
        'ore_hemorphite' => 'setOreHemorphite',
        'ore_ice' => 'setOreIce',
        'ore_jaspet' => 'setOreJaspet',
        'ore_kernite' => 'setOreKernite',
        'ore_mercoxit' => 'setOreMercoxit',
        'ore_omber' => 'setOreOmber',
        'ore_plagioclase' => 'setOrePlagioclase',
        'ore_pyroxeres' => 'setOrePyroxeres',
        'ore_scordite' => 'setOreScordite',
        'ore_spodumain' => 'setOreSpodumain',
        'ore_veldspar' => 'setOreVeldspar'
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
        'drone_mine' => 'getDroneMine',
        'ore_arkonor' => 'getOreArkonor',
        'ore_bistot' => 'getOreBistot',
        'ore_crokite' => 'getOreCrokite',
        'ore_dark_ochre' => 'getOreDarkOchre',
        'ore_gneiss' => 'getOreGneiss',
        'ore_harvestable_cloud' => 'getOreHarvestableCloud',
        'ore_hedbergite' => 'getOreHedbergite',
        'ore_hemorphite' => 'getOreHemorphite',
        'ore_ice' => 'getOreIce',
        'ore_jaspet' => 'getOreJaspet',
        'ore_kernite' => 'getOreKernite',
        'ore_mercoxit' => 'getOreMercoxit',
        'ore_omber' => 'getOreOmber',
        'ore_plagioclase' => 'getOrePlagioclase',
        'ore_pyroxeres' => 'getOrePyroxeres',
        'ore_scordite' => 'getOreScordite',
        'ore_spodumain' => 'getOreSpodumain',
        'ore_veldspar' => 'getOreVeldspar'
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
        $this->container['drone_mine'] = isset($data['drone_mine']) ? $data['drone_mine'] : null;
        $this->container['ore_arkonor'] = isset($data['ore_arkonor']) ? $data['ore_arkonor'] : null;
        $this->container['ore_bistot'] = isset($data['ore_bistot']) ? $data['ore_bistot'] : null;
        $this->container['ore_crokite'] = isset($data['ore_crokite']) ? $data['ore_crokite'] : null;
        $this->container['ore_dark_ochre'] = isset($data['ore_dark_ochre']) ? $data['ore_dark_ochre'] : null;
        $this->container['ore_gneiss'] = isset($data['ore_gneiss']) ? $data['ore_gneiss'] : null;
        $this->container['ore_harvestable_cloud'] = isset($data['ore_harvestable_cloud']) ? $data['ore_harvestable_cloud'] : null;
        $this->container['ore_hedbergite'] = isset($data['ore_hedbergite']) ? $data['ore_hedbergite'] : null;
        $this->container['ore_hemorphite'] = isset($data['ore_hemorphite']) ? $data['ore_hemorphite'] : null;
        $this->container['ore_ice'] = isset($data['ore_ice']) ? $data['ore_ice'] : null;
        $this->container['ore_jaspet'] = isset($data['ore_jaspet']) ? $data['ore_jaspet'] : null;
        $this->container['ore_kernite'] = isset($data['ore_kernite']) ? $data['ore_kernite'] : null;
        $this->container['ore_mercoxit'] = isset($data['ore_mercoxit']) ? $data['ore_mercoxit'] : null;
        $this->container['ore_omber'] = isset($data['ore_omber']) ? $data['ore_omber'] : null;
        $this->container['ore_plagioclase'] = isset($data['ore_plagioclase']) ? $data['ore_plagioclase'] : null;
        $this->container['ore_pyroxeres'] = isset($data['ore_pyroxeres']) ? $data['ore_pyroxeres'] : null;
        $this->container['ore_scordite'] = isset($data['ore_scordite']) ? $data['ore_scordite'] : null;
        $this->container['ore_spodumain'] = isset($data['ore_spodumain']) ? $data['ore_spodumain'] : null;
        $this->container['ore_veldspar'] = isset($data['ore_veldspar']) ? $data['ore_veldspar'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();
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
        return true;
    }


    /**
     * Gets drone_mine
     * @return int
     */
    public function getDroneMine()
    {
        return $this->container['drone_mine'];
    }

    /**
     * Sets drone_mine
     * @param int $drone_mine drone_mine integer
     * @return $this
     */
    public function setDroneMine($drone_mine)
    {
        $this->container['drone_mine'] = $drone_mine;

        return $this;
    }

    /**
     * Gets ore_arkonor
     * @return int
     */
    public function getOreArkonor()
    {
        return $this->container['ore_arkonor'];
    }

    /**
     * Sets ore_arkonor
     * @param int $ore_arkonor ore_arkonor integer
     * @return $this
     */
    public function setOreArkonor($ore_arkonor)
    {
        $this->container['ore_arkonor'] = $ore_arkonor;

        return $this;
    }

    /**
     * Gets ore_bistot
     * @return int
     */
    public function getOreBistot()
    {
        return $this->container['ore_bistot'];
    }

    /**
     * Sets ore_bistot
     * @param int $ore_bistot ore_bistot integer
     * @return $this
     */
    public function setOreBistot($ore_bistot)
    {
        $this->container['ore_bistot'] = $ore_bistot;

        return $this;
    }

    /**
     * Gets ore_crokite
     * @return int
     */
    public function getOreCrokite()
    {
        return $this->container['ore_crokite'];
    }

    /**
     * Sets ore_crokite
     * @param int $ore_crokite ore_crokite integer
     * @return $this
     */
    public function setOreCrokite($ore_crokite)
    {
        $this->container['ore_crokite'] = $ore_crokite;

        return $this;
    }

    /**
     * Gets ore_dark_ochre
     * @return int
     */
    public function getOreDarkOchre()
    {
        return $this->container['ore_dark_ochre'];
    }

    /**
     * Sets ore_dark_ochre
     * @param int $ore_dark_ochre ore_dark_ochre integer
     * @return $this
     */
    public function setOreDarkOchre($ore_dark_ochre)
    {
        $this->container['ore_dark_ochre'] = $ore_dark_ochre;

        return $this;
    }

    /**
     * Gets ore_gneiss
     * @return int
     */
    public function getOreGneiss()
    {
        return $this->container['ore_gneiss'];
    }

    /**
     * Sets ore_gneiss
     * @param int $ore_gneiss ore_gneiss integer
     * @return $this
     */
    public function setOreGneiss($ore_gneiss)
    {
        $this->container['ore_gneiss'] = $ore_gneiss;

        return $this;
    }

    /**
     * Gets ore_harvestable_cloud
     * @return int
     */
    public function getOreHarvestableCloud()
    {
        return $this->container['ore_harvestable_cloud'];
    }

    /**
     * Sets ore_harvestable_cloud
     * @param int $ore_harvestable_cloud ore_harvestable_cloud integer
     * @return $this
     */
    public function setOreHarvestableCloud($ore_harvestable_cloud)
    {
        $this->container['ore_harvestable_cloud'] = $ore_harvestable_cloud;

        return $this;
    }

    /**
     * Gets ore_hedbergite
     * @return int
     */
    public function getOreHedbergite()
    {
        return $this->container['ore_hedbergite'];
    }

    /**
     * Sets ore_hedbergite
     * @param int $ore_hedbergite ore_hedbergite integer
     * @return $this
     */
    public function setOreHedbergite($ore_hedbergite)
    {
        $this->container['ore_hedbergite'] = $ore_hedbergite;

        return $this;
    }

    /**
     * Gets ore_hemorphite
     * @return int
     */
    public function getOreHemorphite()
    {
        return $this->container['ore_hemorphite'];
    }

    /**
     * Sets ore_hemorphite
     * @param int $ore_hemorphite ore_hemorphite integer
     * @return $this
     */
    public function setOreHemorphite($ore_hemorphite)
    {
        $this->container['ore_hemorphite'] = $ore_hemorphite;

        return $this;
    }

    /**
     * Gets ore_ice
     * @return int
     */
    public function getOreIce()
    {
        return $this->container['ore_ice'];
    }

    /**
     * Sets ore_ice
     * @param int $ore_ice ore_ice integer
     * @return $this
     */
    public function setOreIce($ore_ice)
    {
        $this->container['ore_ice'] = $ore_ice;

        return $this;
    }

    /**
     * Gets ore_jaspet
     * @return int
     */
    public function getOreJaspet()
    {
        return $this->container['ore_jaspet'];
    }

    /**
     * Sets ore_jaspet
     * @param int $ore_jaspet ore_jaspet integer
     * @return $this
     */
    public function setOreJaspet($ore_jaspet)
    {
        $this->container['ore_jaspet'] = $ore_jaspet;

        return $this;
    }

    /**
     * Gets ore_kernite
     * @return int
     */
    public function getOreKernite()
    {
        return $this->container['ore_kernite'];
    }

    /**
     * Sets ore_kernite
     * @param int $ore_kernite ore_kernite integer
     * @return $this
     */
    public function setOreKernite($ore_kernite)
    {
        $this->container['ore_kernite'] = $ore_kernite;

        return $this;
    }

    /**
     * Gets ore_mercoxit
     * @return int
     */
    public function getOreMercoxit()
    {
        return $this->container['ore_mercoxit'];
    }

    /**
     * Sets ore_mercoxit
     * @param int $ore_mercoxit ore_mercoxit integer
     * @return $this
     */
    public function setOreMercoxit($ore_mercoxit)
    {
        $this->container['ore_mercoxit'] = $ore_mercoxit;

        return $this;
    }

    /**
     * Gets ore_omber
     * @return int
     */
    public function getOreOmber()
    {
        return $this->container['ore_omber'];
    }

    /**
     * Sets ore_omber
     * @param int $ore_omber ore_omber integer
     * @return $this
     */
    public function setOreOmber($ore_omber)
    {
        $this->container['ore_omber'] = $ore_omber;

        return $this;
    }

    /**
     * Gets ore_plagioclase
     * @return int
     */
    public function getOrePlagioclase()
    {
        return $this->container['ore_plagioclase'];
    }

    /**
     * Sets ore_plagioclase
     * @param int $ore_plagioclase ore_plagioclase integer
     * @return $this
     */
    public function setOrePlagioclase($ore_plagioclase)
    {
        $this->container['ore_plagioclase'] = $ore_plagioclase;

        return $this;
    }

    /**
     * Gets ore_pyroxeres
     * @return int
     */
    public function getOrePyroxeres()
    {
        return $this->container['ore_pyroxeres'];
    }

    /**
     * Sets ore_pyroxeres
     * @param int $ore_pyroxeres ore_pyroxeres integer
     * @return $this
     */
    public function setOrePyroxeres($ore_pyroxeres)
    {
        $this->container['ore_pyroxeres'] = $ore_pyroxeres;

        return $this;
    }

    /**
     * Gets ore_scordite
     * @return int
     */
    public function getOreScordite()
    {
        return $this->container['ore_scordite'];
    }

    /**
     * Sets ore_scordite
     * @param int $ore_scordite ore_scordite integer
     * @return $this
     */
    public function setOreScordite($ore_scordite)
    {
        $this->container['ore_scordite'] = $ore_scordite;

        return $this;
    }

    /**
     * Gets ore_spodumain
     * @return int
     */
    public function getOreSpodumain()
    {
        return $this->container['ore_spodumain'];
    }

    /**
     * Sets ore_spodumain
     * @param int $ore_spodumain ore_spodumain integer
     * @return $this
     */
    public function setOreSpodumain($ore_spodumain)
    {
        $this->container['ore_spodumain'] = $ore_spodumain;

        return $this;
    }

    /**
     * Gets ore_veldspar
     * @return int
     */
    public function getOreVeldspar()
    {
        return $this->container['ore_veldspar'];
    }

    /**
     * Sets ore_veldspar
     * @param int $ore_veldspar ore_veldspar integer
     * @return $this
     */
    public function setOreVeldspar($ore_veldspar)
    {
        $this->container['ore_veldspar'] = $ore_veldspar;

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


