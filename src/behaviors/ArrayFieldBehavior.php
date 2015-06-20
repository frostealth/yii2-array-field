<?php

namespace frostealth\yii2\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
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
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'decode',
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
            $value = $this->owner->{$attribute};
            $value = !empty($value) ? Json::encode($value) : $this->defaultEncodedValue;

            $this->owner->{$attribute} = $value;
        }
    }

    /**
     * Decode attributes
     */
    public function decode()
    {
        foreach ($this->attributes as $attribute) {
            $value = Json::decode($this->owner->{$attribute});

            $this->owner->{$attribute} = !empty($value) ? $value : $this->defaultDecodedValue;
        }
    }
}
