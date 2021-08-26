<?php

/**
 * Email List plugin for Craft CMS 3.x
 *
 * A plugin for saving emails to a list
 *
 * @link      https://github.com/luke-hopkins
 * @copyright Copyright (c) 2020 Luke Hopkins
 */

namespace lukehopkins\ordersamples\models;

use Craft;
use craft\base\Model;

/**
 * Email Model
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Luke Hopkins
 * @package   EmailList
 * @since     1.0.0
 */
class SampleRequest extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some model attribute
     *
     * @var string
     */
    public $email = '';
    public $product_code = '';
    public $product_name = '';
    public $name = '';
    public $address = '';
    public $address2 = '';
    public $address3 = '';
    public $postcode = '';
    public $phone = '';
    public $status = '';

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'string'],
            ['email', 'string'],
            ['address', 'string'],
            ['address2', 'string'],
            ['address3', 'string'],
            ['postcode', 'string'],
            ['phone', 'string'],
            ['product_name', 'string'],
            ['product_code', 'string'],
            ['status', 'string'],
        ];
    }
}
