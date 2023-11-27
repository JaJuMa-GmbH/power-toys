<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Jajuma\PowerToys\Block;

use Jajuma\PowerToys\Model\Config;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Url;
use Magento\Framework\View\Element\Template;
use Jajuma\PowerToys\Model\ResourceModel\BookMark\CollectionFactory as BookMarkCollectionFactory;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Backend\Model\Url as BackendUrl;
use Jajuma\PowerToys\Helper\Data;
use Jajuma\PowerToys\Model\System\Config\Backend\Icon;
use \Magento\Backend\Setup\ConfigOptionsList as BackendConfigOptionsList;
use Magento\Framework\App\DeploymentConfig;

class PowerToys extends Template
{
    private $powerToyConfig;

    protected $formKey;

    private $bookMarkCollectionFactory;

    protected $storeManager;

    protected $backendUrlManager;

    protected $helper;

    protected $url;

    protected $backendSuffix = '';

    private $_state;

    public function __construct(
        Config $powerToyConfig,
        FormKey $formKey,
        Template\Context $context,
        BookMarkCollectionFactory $bookMarkCollectionFactory,
        StoreManagerInterface $storeManager,
        BackendUrl $backendUrlManager,
        Data $helper,
        Url $url,
        DeploymentConfig $deploymentConfig,
        \Magento\Framework\App\State $state,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->powerToyConfig = $powerToyConfig;
        $this->formKey = $formKey;
        $this->bookMarkCollectionFactory = $bookMarkCollectionFactory;
        $this->storeManager = $storeManager;
        $this->backendUrlManager = $backendUrlManager;
        $this->helper = $helper;
        $this->backendSuffix = $deploymentConfig->get(BackendConfigOptionsList::CONFIG_PATH_BACKEND_FRONTNAME);
        $this->url = $url;
        $this->_state = $state;
    }

    public function getBlocks($type) {
        $blockArr = [];
        $blockConfigArr = $this->powerToyConfig->getWidget($type);
        $sortOrderConfig = $this->helper->loadComponentSortOrderConfig($type) ?? [];
        if ($sortOrderConfig) {
            $sortOrderConfig = json_decode($sortOrderConfig, true);
        }
        foreach ($blockConfigArr as $block_id => $blockConfig) {
            $block = $this->getLayout()->getBlock($block_id);
            if ($block) {
                if ($block->isEnable()) {
                    if (array_key_exists($block_id, $sortOrderConfig)) {
                        $block->setData('sort_order', $sortOrderConfig[$block_id]);
                    }
                    $blockArr[] = $block;
                }
            }
        }
        usort($blockArr, function ($item1, $item2) {
            return (intval($item1['sort_order'] ?? '0') - intval($item2['sort_order'] ?? '0'));
        });
        return $blockArr;
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    public function getBackendSuffix()
    {
        return $this->backendSuffix;
    }

    public function getBookMarkCollection()
    {
        $bookMarkCollectionFactory =  $this->bookMarkCollectionFactory->create();
        $bookMarkCollectionFactory->setOrder('position','ASC');
        return $bookMarkCollectionFactory;
    }

    public function getBaseUrlMedia()
    {
        return $this->storeManager->getStore()->getBaseUrl(
            UrlInterface::URL_TYPE_MEDIA
        );
    }

    public function getUrlMediaIcon($icon)
    {
        if (strpos($icon, 'wysiwyg/') !== false) {
            return $this->getBaseUrlMedia() . $icon;
        }
        return $this->getBaseUrlMedia() . 'jajuma_powertoys/bookmark/icon/' . $icon;
    }

    public function getBookMarkUrl($path, $params = [])
    {
        if (strpos($path, $this->getBackendSuffix()) !== false) {
            $path = preg_replace('/' . $this->getBackendSuffix() . '\//', '', $path, 1);
            return $this->generateUrlAdmin($path, $params);
        } else {
            return $this->storeManager->getStore()->getBaseUrl() . $path;
        }
    }

    public function generateUrlAdmin($path, $params = [])
    {
        return $this->backendUrlManager->getUrl($path, ['redirectFromBookmark' => true]);
    }

    public function getDefaultIcon()
    {
        $iconConfig = $this->helper->getDefaultIcon();
        if ($iconConfig) {
            return $this->getBaseUrlMedia() . Icon::UPLOAD_DIR . '/' . $iconConfig;
        }
        return $this->getViewFileUrl('Jajuma_PowerToys::images/jajuma_develop.png');
    }

    public function isEnabledQuickAction()
    {
        return $this->helper->isEnabledQuickAction();
    }

    public function isEnabledWidget()
    {
        return $this->helper->isEnabledWidget();
    }

    public function isEnabledBookMark()
    {
        return $this->helper->isEnabledBookMark();
    }

    public function getStoreUrl($routePath, $routeParams = [])
    {
        return $this->url->getUrl($routePath, $routeParams);
    }

    public function toHtml()
    {
        if ($this->helper->isAllowedPowerToys()){
            return parent::toHtml();
        }
        return '';
    }

    public function isAdmin() {
        $areaCode = $this->_state->getAreaCode();
        return $areaCode == \Magento\Framework\App\Area::AREA_ADMINHTML;
    }
}
