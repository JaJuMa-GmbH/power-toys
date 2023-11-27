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
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Template;
use Magewirephp\Magewire\ViewModel\Magewire;

class Render implements ActionInterface
{
    private $request;
    private $resultFactory;

    private $helper;

    public function __construct(
        Context $context,
        Data $helper
    ) {
        $this->request = $context->getRequest();
        $this->resultFactory = $context->getResultFactory();
        $this->helper = $helper;
    }

    public function execute()
    {
        $sectionName = $this->request->getParam('section_name');
        if ($this->helper->isAllowedPowerToys()){
            $resultLayout = $this->resultFactory->create('layout');
            $layout = $resultLayout->getLayout();
            if ($sectionName == 'jajuma_power_toys') {
                $block = $layout->createBlock(PowerToys::class)
                    ->setBlockId('jajuma_power_toys')
                    ->setTemplate('Jajuma_PowerToys::powertoys.phtml')
                    ->setNameInLayout('jajuma_power_toys');
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
            if ($sectionName == 'jajuma_power_toys_magewire') {
                $viewModel = ObjectManager::getInstance()->create(Magewire::class);
                $block = $layout->createBlock(Template::class)
                    ->setBlockId('jajuma_power_toys_magewire')
                    ->setTemplate('Jajuma_PowerToys::magewire.phtml')
                    ->setViewModel($viewModel)
                    ->setNameInLayout('jajuma_power_toys_magewire');
                $html = $block->toHtml();
                $scriptJs = $this->getScriptFromHtml($html);
                $result = $this->resultFactory->create('raw');
                $result->setContents($scriptJs);
                $result->setHeader('Content-Type', 'application/javascript', true);
                $result->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
                return $result;
            }
        }
        $result = $this->resultFactory->create('raw');
        $result->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
        return $result->setContents('Forbidden !')->setHttpResponseCode(403);
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

