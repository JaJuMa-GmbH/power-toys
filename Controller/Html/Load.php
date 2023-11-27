<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Jajuma\PowerToys\Controller\Html;

use Jajuma\PowerToys\Helper\Data;
use Jajuma\PowerToys\Model\Config;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;

class Load implements ActionInterface
{
    protected $request;
    private $resultFactory;
    private $powerToyConfig;
    private $helper;

    public function __construct(
        Context $context,
        Config $powerToyConfig,
        Data $helper
    ) {
        $this->request = $context->getRequest();
        $this->resultFactory = $context->getResultFactory();
        $this->powerToyConfig = $powerToyConfig;
        $this->helper = $helper;
    }

    public function execute()
    {
        $type = $this->request->getParam('type');
        $actionId = $this->request->getParam('action_id');
        $result = $this->resultFactory->create('raw');
        if ($this->helper->isAllowedPowerToys()){
            if ($actionId && $type) {
                if (in_array($type, Data::COMPONENT_TYPE_ARR)) {
                    $resultLayout = $this->resultFactory->create('layout');
                    $layout = $resultLayout->getLayout();
                    $blockConfig = $this->powerToyConfig->getWidget("$type/$actionId");
                    if ($blockConfig && is_array($blockConfig) && count($blockConfig) > 0) {
                        $block = $this->helper->createPowerToysBlock($layout, $actionId, $blockConfig);
                        if ($block->isEnable()) {
                            $html = $block->toHtml();
                            $scriptJs = $this->getScriptFromHtml($html);
                            $result = $this->resultFactory->create('json');
                            $result->setData([
                                'html' => $html,
                                'js' => $scriptJs
                            ]);
                            $result->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
                            return $result;
                        }
                    }
                    $result->setContents('Not Found')->setHttpResponseCode(404);
                }
            }
            $result->setContents('Bad Request')->setHttpResponseCode(400);
        }
        $result->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
        $result->setContents('Forbidden')->setHttpResponseCode(403);
        return $result;
    }

    private function getScriptFromHtml($html):string {
        preg_match_all('#<script(.*?)>(.*?)</script>#is', $html, $matches);
        $scriptMatches = $matches[2] ?? [];
        $scriptJs = '';
        if (is_array($scriptMatches) && (count($scriptMatches) > 0)){
            foreach ($scriptMatches as $script) {
                $scriptJs .= $script . "\n";
            }
        }
        return $scriptJs;
    }
}

