<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Jajuma\PowerToys\Helper;

use Jajuma\PowerToys\Model\Auth;
use Jajuma\PowerToys\Model\Config;
use Magento\Backend\Model\Auth\Session;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Math\Random;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 * @package Jajuma\PowerToys\Helper
 */
class Data extends AbstractHelper
{
    const COMPONENT_TYPE_ARR = ['quickaction', 'dashboard'];
    public const ENABLE_EXTENSION = 'power_toys/general/enable';

    public const POWER_TOYS_ICON = 'power_toys/general/icon';

    public const POWER_TOYS_ENABLE_QUICKACTION = 'power_toys/general/enable_quickaction';

    public const POWER_TOYS_ENABLE_DASHBOARD = 'power_toys/general/enable_dashboard';

    public const POWER_TOYS_ENABLE_BOOKMARK = 'power_toys/general/enable_bookmark';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    protected $powerToysAuth;

    private $powerToyConfig;

    private $configWriter;

    private $configCollection;

    /**
     * Data constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Auth $powerToysAuth
     * @param Config $powerToyConfig
     * @param WriterInterface $configWriter
     * @param CollectionFactory $configCollection
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Auth $powerToysAuth,
        Config $powerToyConfig,
        WriterInterface $configWriter,
        CollectionFactory $configCollection
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->powerToysAuth = $powerToysAuth;
        $this->powerToyConfig = $powerToyConfig;
        $this->configWriter = $configWriter;
        $this->configCollection = $configCollection;
    }

    /**
     * @param null $store
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return $this->scopeConfig->getValue(
            self::ENABLE_EXTENSION,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null $store
     * @return string
     */
    public function getDefaultIcon($store = null)
    {
        return $this->scopeConfig->getValue(
            self::POWER_TOYS_ICON,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function isEnabledQuickAction($store = null)
    {
        return $this->scopeConfig->getValue(
            self::POWER_TOYS_ENABLE_QUICKACTION,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null $store
     * @return bool
     */
    public function isEnabledWidget($store = null)
    {
        return $this->scopeConfig->getValue(
            self::POWER_TOYS_ENABLE_DASHBOARD,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null $store
     * @return bool
     */
    public function isEnabledBookMark($store = null)
    {
        return $this->scopeConfig->getValue(
            self::POWER_TOYS_ENABLE_BOOKMARK,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param $path
     * @param null $store
     * @return mixed
     */
    public function getScopeConfig($path, $store = null)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function createPowerToysBlock($layout, $blockId, $blockConfig) {
        $type = $blockConfig['@']['type'];
        $name = $blockConfig['name'];
        $arguments = $blockConfig['arguments'] ?? [];
        $block = $layout->createBlock($type)->setBlockId($blockId);
        //Block lazyload by default
//        $block->setData('lazyload', true);
        $block->setData('sort_order', 500);
        $block->setData('cache_lifetime', 86400);
        foreach ($arguments as $key => $value) {
            if ($key == 'template') {
                $block->setTemplate($value);
                continue;
            }
            if ($key == 'magewire') {
                $value = trim($value);
                if ($value && ($value != '')) {
                    try{
                        $magewireObject = ObjectManager::getInstance()->create($value);
                        $block->setData('magewire', $magewireObject);
                    }catch (\Exception $e) {
                        $this->_logger->error($e->getMessage());
                    }
                }
                continue;
            }
            $block->setData($key, $value);
        }
        $block->setName($name);
        $block->setNameInLayout($blockId);
        return $block;
    }


    public function isAllowedPowerToys()
    {
        return $this->powerToysAuth->isAllowedPowerToys();
    }

    public function getMagewireComponentIdArr() {
        $dashboardComponents = $this->powerToyConfig->getWidget('dashboard');
        $quickActionComponents = $this->powerToyConfig->getWidget('quickaction');
        $bookmarkComponentId = 'jajuma_power_toys_bookmark_bar';
        $componentIdArr = [];
        $componentIdArr[] = $bookmarkComponentId;
        foreach ($dashboardComponents as $componentId => $config) {
            if (isset($config['arguments']['magewire'])){
                $componentIdArr[] = $componentId;
            }
        }
        foreach ($quickActionComponents as $componentId => $config) {
            if (isset($config['arguments']['magewire'])){
                $componentIdArr[] = $componentId;
            }
        }
        return $componentIdArr;
    }

    public function getPowerToysComponentWithType($type) {
        $componentIdArr = [];
        $components = $this->powerToyConfig->getWidget($type);
        foreach ($components as $componentId => $config) {
            $componentIdArr[] = $componentId;
        }
        return $componentIdArr;
    }

    /**
     * @param string $type
     * @param string $data
     * @param string $scope
     * @param int $scopeId
     * @return void
     */
    public function saveComponentSortOrderConfig(string $type, string $data, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0): void {
        $path = "power_toys/general/sort_order/$type";
        $this->configWriter->save($path, $data, $scope, $scopeId);
    }

    /**
     * @param string $type
     * @return string|null
     */
    public function loadComponentSortOrderConfig(string $type): ?string {
        $path = "power_toys/general/sort_order/$type";
        $collection = $this->configCollection->create();
        $collection->addFieldToFilter("path", ['eq' => $path]);
        if($collection->count() > 0){
            return $collection->getFirstItem()->getData()['value'];
        }
        else {
            return null;
        }
    }

}
