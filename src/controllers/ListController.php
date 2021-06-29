<?php
namespace lukehopkins\ordersamples\controllers;

use Craft;
use craft\web\Controller;
use lukehopkins\ordersamples\SampleRequest;
use lukehopkins\ordersamples\models\SampleRequest as SampleRequestModel;
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
        $name = Craft::$app->request->getQueryParam('name');
        $email = Craft::$app->request->getQueryParam('email');
        $address = Craft::$app->request->getQueryParam('address');
        $postcode = Craft::$app->request->getQueryParam('postcode');
        $phone = Craft::$app->request->getQueryParam('phone');
        $product_name = Craft::$app->request->getQueryParam('product_name');
        $product_code = Craft::$app->request->getQueryParam('product_code');

        $model = new SampleRequestModel();
        $model->setAttributes([
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'postcode' => $postcode,
            'phone' => $phone,
            'product_name' => $product_name,
            'product_code' => $product_code,
            'status' => 'New'
        ]);
        SampleRequest::$plugin->sampleRequest->saveRequest($model);

        $oldMode = \Craft::$app->view->getTemplateMode();
        \Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_CP);

        $html = $this->renderTemplate('order-samples/email', [
            'date' => date('d/m/Y - H:i'),
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'postcode' => $postcode,
            'phone' => $phone,
            'product_name' => $product_name,
            'product_code' => $product_code,
            'status' => 'New'
        ]);

        \Craft::$app->view->setTemplateMode($oldMode);

        Craft::$app
            ->getMailer()
            ->compose()
            ->setTo('help@timneyfowler.com')
            ->setSubject('New Sample Order - '.$product_name.' ('.$product_code.')')
            ->setHtmlBody($html)
            ->send();

        Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_SITE);
        $html = Craft::$app->view->renderTemplate('email/sample-order', [
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'postcode' => $postcode,
            'phone' => $phone,
            'product_name' => $product_name,
            'product_code' => $product_code,
            'status' => 'New'
        ]);

        \Craft::$app
            ->getMailer()
            ->compose()
            ->setTo($email)
            ->setSubject('Timney Fowler - Sample Order')
            ->setHtmlBody($html)
            ->send();

        return $this->redirect(Craft::$app->request->getQueryParam('redirect'));
    }
}
