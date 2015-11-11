<?php

namespace frostealth\yii2\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\helpers\Json;

/**
 * Class ArrayFieldBehavior
 *
 * ~~~
 * use frostealth\yii2\behaviors\ArrayFieldBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => ArrayFieldBehavior::className(),
 *             'attributes' => ['attribute1', 'attribute2'],
 *             'defaultEncodedValue' => 'some value',
 *             'defaultDecodedValue' => 'some value',
 *         ],
 *     ];
 * }
 * ~~~
 *
 * @property BaseActiveRecord $owner
 *
 * @package frostealth\yii2\behaviors
 */
class ArrayFieldBehavior extends Behavior
{
    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var mixed
     */
    public $defaultEncodedValue = null;

    /**
     * @var mixed
     */
    public $defaultDecodedValue = [];

    /**
     * @var array
     */
    private $_cache = [];

    /**
     * @var array
     */
    private $_oldAttributes = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_FIND => 'decode',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'decode',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'decode',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'encode',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'encode',
        ];
    }

    /**
     * Encode attributes
     */
    public function encode()
    {
        foreach ($this->attributes as $attribute) {
            if (isset($this->_oldAttributes[$attribute])) {
                $this->owner->setOldAttribute($attribute, $this->_oldAttributes[$attribute]);
            }

            $value = $this->owner->getAttribute($attribute);
            $this->_cache[$attribute] = $value;

            $value = !empty($value) ? Json::encode($value) : $this->defaultEncodedValue;
            $this->owner->setAttribute($attribute, $value);
        }
    }

    /**
     * Decode attributes
     */
    public function decode()
    {
        foreach ($this->attributes as $attribute) {
            if (isset($this->_cache[$attribute])) {
                $value = $this->_cache[$attribute];
            } else {
                $value = Json::decode($this->owner->getAttribute($attribute));
            }

            $value = !empty($value) ? $value : $this->defaultDecodedValue;
            $this->owner->setAttribute($attribute, $value);

            if (!$this->owner->getIsNewRecord()) {
                $this->_oldAttributes[$attribute] = $this->owner->getOldAttribute($attribute);
                $this->owner->setOldAttribute($attribute, $value);
            }
        }

        $this->_cache = [];
    }
}
