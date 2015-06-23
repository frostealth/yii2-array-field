<?php

namespace frostealth\yii2\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
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
 * @property ActiveRecord $owner
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
            ActiveRecord::EVENT_AFTER_FIND => 'decode',
            ActiveRecord::EVENT_AFTER_INSERT => 'decode',
            ActiveRecord::EVENT_AFTER_UPDATE => 'decode',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'encode',
            ActiveRecord::EVENT_BEFORE_INSERT => 'encode',
        ];
    }

    /**
     * Encode attributes
     */
    public function encode()
    {
        foreach ($this->attributes as $attribute) {
            if (!$this->owner->getIsNewRecord()) {
                $oldValue = ArrayHelper::getValue($this->_oldAttributes, $this->defaultEncodedValue);
                $this->owner->setOldAttribute($attribute, $oldValue);
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
            if (!empty($this->_cache[$attribute])) {
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
