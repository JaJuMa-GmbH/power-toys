<?php

namespace Jajuma\PowerToys\Plugin\App\Request;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Backend\App\Request\BackendValidator as MagentoBackendValidator;
use Magento\Backend\App\AbstractAction;
use Magento\Framework\App\Request\InvalidRequestException;

class BackendValidator
{
    public function aroundValidate(
        MagentoBackendValidator $subject,
        callable $proceed,
        RequestInterface $request,
        ActionInterface $action
    ) {
        if ($action instanceof AbstractAction) {
            //Abstract Action has built-in validation.
            if (!$request->getParams('redirectFromBookmark')) {
                if (!$action->_processUrlKeys()) {
                    throw new InvalidRequestException($action->getResponse());
                }
            }
        } else {
            //Fallback validation.
            $proceed($request, $action);
        }
    }
}
