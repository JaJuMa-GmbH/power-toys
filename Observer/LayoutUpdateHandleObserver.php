<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Jajuma\PowerToys\Observer;

use Jajuma\PowerToys\Helper\Data;
use Jajuma\PowerToys\Model\Auth;
use Jajuma\PowerToys\Model\Config;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Layout as Layout;

class LayoutUpdateHandleObserver implements ObserverInterface
{
    private $powerToyConfig;

    private $powerToyAuth;

    private $helper;

    public function __construct(
        Config $powerToyConfig,
        Auth $powerToyAuth,
        Data $helper
    ) {
        $this->powerToyConfig = $powerToyConfig;
        $this->powerToyAuth = $powerToyAuth;
        $this->helper = $helper;
    }

    /**
     * @param EventObserver $observer
     *
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $event = $observer->getEvent();
        /** @var Layout $layout */
        $layout = $event->getData('layout');
        if ($this->helper->isEnabledQuickAction()) {
            $this->createBlocks('quickaction', $layout);
        }
        if ($this->helper->isEnabledWidget()) {
            $this->createBlocks('dashboard', $layout);
        }
        if ($this->helper->isEnabledBookMark()) {
            $this->createBookmarkBlock($layout);
        }
    }

    private function createBookmarkBlock($layout) {
        //Add Bookmarkbar block here
        $layout->createBlock(\Jajuma\PowerToys\Block\BookmarkBar::class)
            ->setTemplate('Jajuma_PowerToys::bookmark_bar.phtml')
            ->setNameInLayout('jajuma_power_toys_bookmark_bar')
            ->setData('magewire', ObjectManager::getInstance()->create(\Jajuma\PowerToys\Magewire\BookmarkBar::class))
            ->setData('hero_icons_outline', ObjectManager::getInstance()->create(\Jajuma\PowerToys\ViewModel\HeroiconsOutline::class));
    }

    private function createBlocks($type, $layout) {
        $blockArr = [];
        $blockConfigArr = $this->powerToyConfig->getWidget($type);
        foreach ($blockConfigArr as $block_id => $blockConfig) {
            $isEnable = $blockConfig['arguments']['enable'] ?? 'false';
            $aclResource = $blockConfig['arguments']['acl_resource'] ?? false;
            if ($isEnable == 'true') {
                if ($aclResource && ($aclResource != '')) {
                    //check acl resource
                    if (!$this->powerToyAuth->validateAdminAcl($aclResource)){
                        continue;
                    }
                }
                $block = $this->helper->createPowerToysBlock($layout, $block_id, $blockConfig);
                $blockArr[] = $block;
            }
        }
        return $blockArr;
    }
}
