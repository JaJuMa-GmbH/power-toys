<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Jajuma\PowerToys\Controller\Html;

use Jajuma\PowerToys\Block\PowerToys;
use Jajuma\PowerToys\Helper\Data;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template;
use Magewirephp\Magewire\ViewModel\Magewire;
use Jajuma\PowerToys\Model\BookMarkFactory;
use Psr\Log\LoggerInterface;

class Sort implements ActionInterface, CsrfAwareActionInterface
{
    private $request;

    private $resultFactory;

    private $helper;

    private $bookmarkFactory;

    private $logger;

    public function __construct(
        Context $context,
        Data $helper,
        BookMarkFactory $bookmarkFactory,
        LoggerInterface $logger
    ) {
        $this->request = $context->getRequest();
        $this->resultFactory = $context->getResultFactory();
        $this->helper = $helper;
        $this->logger = $logger;
        $this->bookmarkFactory = $bookmarkFactory;
    }

    public function execute()
    {
        $result = $this->resultFactory->create('raw');
        $result->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
        if ($this->helper->isAllowedPowerToys()){
            $json = file_get_contents('php://input');
            $postData = json_decode($json, true);
            $type = $postData['type'] ?? null;
            $itemData = $postData['data'] ?? [];
            $itemData = array_unique($itemData);
            if (in_array($type, Data::COMPONENT_TYPE_ARR) && is_array($itemData) && (count($itemData) > 1)) {
                $curComponent = $this->helper->getPowerToysComponentWithType($type);
                $sortArr = array_flip(array_values(array_intersect($itemData, $curComponent)));
                $this->helper->saveComponentSortOrderConfig($type, json_encode($sortArr));
                return $result->setHttpResponseCode(200);
            } elseif ($type == 'bookmark' && is_array($itemData) && (count($itemData) > 1)) {
                $i = 1;
                foreach($itemData as $item) {
                    $bookMarkModel = $this->bookmarkFactory->create();
                    try {
                        $bookMarkModel->load($item);
                        $bookMarkModel->setData('position', $i++);
                        $bookMarkModel->save();
                    } catch (\Exception $e) {
                        $this->logger->error($e->getMessage()); 
                    }
                }
                return $result->setHttpResponseCode(200);
            }
            $result->setContents('Bad Request')->setHttpResponseCode(400);
        }
        return $result->setContents('Forbidden !')->setHttpResponseCode(403);
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}

