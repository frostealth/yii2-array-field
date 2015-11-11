# Yii2 Array Field Behavior

This Yii2 model behavior allows you to store arrays in attributes.

## Installation

Run the [Composer](http://getcomposer.org/download/) command to install the latest stable version:

```
composer require frostealth/yii2-array-field @stable
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
            // 'defaultEncodedValue' => null,
            // 'defaultDecodedValue' => [],
        ],
    ];
}
```

## License

The MIT License (MIT).
See [LICENSE.md](https://github.com/frostealth/yii2-array-field/blob/master/LICENSE.md) for more information.