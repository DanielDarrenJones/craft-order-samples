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
    protected array|int|bool $allowAnonymous = true;

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

        $name = Craft::$app->request->getParam('name');
        $email = Craft::$app->request->getParam('email');
        $address = Craft::$app->request->getParam('address');
        $address2 = Craft::$app->request->getParam('address2');
        $address3 = Craft::$app->request->getParam('address3');
        $postcode = Craft::$app->request->getParam('postcode');
        $country = Craft::$app->request->getParam('country');
        $phone = Craft::$app->request->getParam('phone');


        $products = Craft::$app->request->getParam('products');

        // Create the request
        $recordModel = new SampleRequestModel();
        $recordModel->setAttributes([
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
        $record = SampleRequest::$plugin->sampleRequest->saveRequest($recordModel);

        // \Craft::dd($record);

        // Loop through the products and save them to the request
        foreach ($products as $product) {
            $productModel = new SampleRequestProductModel();
            $productModel->setAttributes([
                'requestId' => $record->id,
                'product_name' => $product['name'],
                'product_code' => $product['code'],
            ]);
            SampleRequest::$plugin->sampleRequest->saveProduct($productModel);
        }

        // \Craft::dd($record);


        \Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_SITE);

        $html = Craft::$app->view->renderTemplate('email/sample-order', [
            'record' => $record,
        ]);

        \Craft::$app
            ->getMailer()
            ->compose()
            ->setTo($email)
            ->setBcc('help@timneyfowler.com')
            ->setSubject('Timney Fowler - Sample Order')
            ->setHtmlBody($html)
            ->send();

        return $this->redirect(Craft::$app->request->getValidatedBodyParam('redirect'));
    }
}
