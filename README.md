# Yii2 Array Field Behavior

## Installation
Run the [Composer](http://getcomposer.org/download/) command to install the latest stable version:
```
composer require frostealth/yii2-array-field
```

## Usage
Just attach the behavior to your model.
```php
use frostealth\yii2\behaviors\ArrayFieldBehavior;

public function behaviors()
{
    return [
        [
            'class' => ArrayFieldBehavior::className(),
            'attributes' => ['attribute1', 'attribute2'],
            // 'defaultEncodedValue' => 'some value',
            // 'defaultDecodedValue' => 'some value',
        ],
    ];
}
```