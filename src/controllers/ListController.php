<?php

namespace lukehopkins\ordersamples\controllers;

use Craft;
use craft\web\Controller;
use lukehopkins\ordersamples\SampleRequest;
use lukehopkins\ordersamples\models\SampleRequest as SampleRequestModel;
use lukehopkins\ordersamples\models\SampleRequestProduct as SampleRequestProductModel;
use craft\web\View;

class ListController extends Controller
{
    protected $allowAnonymous = true;

    public function actionIndex()
    {
        $this->requireCpRequest();
        $data['orders'] = SampleRequest::$plugin->sampleRequest->getRequests();
        return $this->renderTemplate('order-samples/list', $data);
    }

    public function actionShipped()
    {
        $this->requireCpRequest();
        $id = Craft::$app->request->getQueryParam('id');
        $order = SampleRequest::$plugin->sampleRequest->getRequest($id);
        $order->status = 'Shipped';
        $order->save();
        $data['orders'] = SampleRequest::$plugin->sampleRequest->getRequests();
        return $this->renderTemplate('order-samples/list', $data);
    }

    public function actionSave()
    {
        \Craft::dd(Craft::$app->request->queryParams());

        $name = Craft::$app->request->getQueryParam('name');
        $email = Craft::$app->request->getQueryParam('email');
        $address = Craft::$app->request->getQueryParam('address');
        $address2 = Craft::$app->request->getQueryParam('address2');
        $address3 = Craft::$app->request->getQueryParam('address3');
        $postcode = Craft::$app->request->getQueryParam('postcode');
        $country = Craft::$app->request->getQueryParam('country');
        $phone = Craft::$app->request->getQueryParam('phone');


        $product_name = Craft::$app->request->getQueryParam('product_name');
        $product_code = Craft::$app->request->getQueryParam('product_code');

        // Create the request
        $model = new SampleRequestModel();
        $model->setAttributes([
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'address2' => $address2,
            'address3' => $address3,
            'postcode' => $postcode,
            'country' => $country,
            'phone' => $phone,
            'status' => 'New'
        ]);
        $record = SampleRequest::$plugin->sampleRequest->saveRequest($model);

        // Loop through the products and save them to the request
        $model = new SampleRequestProductModel();
        $model->setAttributes([
            'requestId' => $record->id,
            'product_name' => $product_name,
            'product_code' => $product_code,
        ]);
        $record = SampleRequest::$plugin->sampleRequest->saveRequest($model);

        \Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_SITE);

        $html = Craft::$app->view->renderTemplate('email/sample-order', [
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'address2' => $address2,
            'address3' => $address3,
            'postcode' => $postcode,
            'country' => $country,
            'phone' => $phone,
            'status' => 'New',

            'products' => [
                'product_name' => $product_name,
                'product_code' => $product_code,
            ]
        ]);

        \Craft::$app
            ->getMailer()
            ->compose()
            ->setTo($email)
            ->setBcc('help@timneyfowler.com')
            ->setSubject('Timney Fowler - Sample Order')
            ->setHtmlBody($html)
            ->send();

        return $this->redirect(Craft::$app->request->getQueryParam('redirect'));
    }
}
