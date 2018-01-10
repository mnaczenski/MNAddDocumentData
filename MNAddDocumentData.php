<?php
namespace MNAddDocumentData;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;

class MNAddDocumentData extends \Shopware\Components\Plugin
{
    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(ActivateContext::CACHE_LIST_DEFAULT);
    }
    public function deactivate(DeactivateContext $context)
    {
        $context->scheduleClearCache(DeactivateContext::CACHE_LIST_DEFAULT);
    }

    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Components_Document::assignValues::after' => 'addAttributes'
        ];
    }

    public function addAttributes(\Enlight_Hook_HookArgs $args)
    {
        /* @var \Shopware_Components_Document $document */
        $document = $args->getSubject();
        $order = $document->_order;
        $view = $document->_view;
        $userData = $view->getTemplateVars('User');

        $service = $this->container->get('shopware_attribute.data_loader');
        $sqlUserAttributes = [
            'attributes' => $service->load('s_user_attributes', $order->userID)
        ];

        $userData = $userData + $sqlUserAttributes;

        $view->assign('User', $userData);
    }
}