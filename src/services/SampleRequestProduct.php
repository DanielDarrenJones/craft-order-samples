<?php

/**
 * Email List plugin for Craft CMS 3.x
 *
 * A plugin for saving emails to a list
 *
 * @link      https://github.com/luke-hopkins
 * @copyright Copyright (c) 2020 Luke Hopkins
 */

namespace lukehopkins\ordersamples\services;

use Craft;
use craft\base\Component;
use lukehopkins\ordersamples\records\SampleRequestProduct as SampleRequestProductRecord;

/**
 * Email Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Luke Hopkins
 * @package   EmailList
 * @since     1.0.0
 */
class SampleRequest extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     EmailList::$plugin->email->exampleService()
     *
     * @return mixed
     */

    public function saveRequest($model)
    {
        $record = new SampleRequestProductRecord();
        $record->product_name = $model->product_name;
        $record->product_code = $model->product_code;
        $save = $record->save();
        return $save;
    }
}
