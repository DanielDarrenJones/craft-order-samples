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

    public function actionSave()
    {
        $name = Craft::$app->request->getQueryParam('name');
        $email = Craft::$app->request->getQueryParam('email');
        $address = Craft::$app->request->getQueryParam('address');
        $postcode = Craft::$app->request->getQueryParam('postcode');
        $phone = Craft::$app->request->getQueryParam('phone');
        $product_name = Craft::$app->request->getQueryParam('product_name');

        $model = new SampleRequestModel();
        $model->setAttributes([
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'postcode' => $postcode,
            'phone' => $phone,
            'product_name' => $product_name,
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
        ]);

        \Craft::$app->view->setTemplateMode($oldMode);

        Craft::$app
            ->getMailer()
            ->compose()
            ->setTo('studio@timneyfowler.com')
            ->setSubject('New Sample Order - '.$product_name)
            ->setHtmlBody($html)
            ->send();

        Craft::$app
            ->getMailer()
            ->compose()
            ->setTo('admin@skyeglobal.uk')
            ->setSubject('New Sample Order - '.$product_name)
            ->setHtmlBody($html)
            ->send();

        return $this->redirect(Craft::$app->request->getQueryParam('redirect'));
    }
}
