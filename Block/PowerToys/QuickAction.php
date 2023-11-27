<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Jajuma\PowerToys\Block\PowerToys;

use Magento\Framework\View\Element\Template;

/**
 * Default block for Quick Action
 * This class can be extendable
 */
class QuickAction extends Template
{
    /**
     * @var string
     */
    protected $widgetIcon = 'Jajuma_PowerToys::images/jajuma_develop.png'; //Add icon here

    /**
     * @var string
     */
    protected $_template = 'Jajuma_PowerToys::default_templates/quickaction.phtml'; //Default template

    /**
     * @var string
     */
    protected $actionType = 'popup';

    /**
     * Is enable
     *
     * @return true
     */
    public function isEnable(): bool
    {
        return $this->getData('enable') == 'true';
    }

    public function getName(): string {
        return $this->getData('name');
    }

    /**
     * @return bool
     */
    public function isLazyload(): bool
    {
        return $this->getData('lazyload') == 'true';
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        if ($this->getData('widget_icon')) {
            $this->widgetIcon = $this->getData('widget_icon');
        }
        return $this->widgetIcon ? $this->getViewFileUrl($this->widgetIcon) : null;
    }

    /**
     * @return string
     */
    public function getQuickActionType(): string
    {
        if ($this->getData('action_type')) {
            $this->actionType = $this->getData('action_type');
        }
        return $this->actionType;
    }

    /**
     * @return string
     */
    public function onClickAction(): string
    {
        if ($this->getQuickActionType() == 'popup') {
            return $this->performPopupAction();
        }
        return 'return false';
    }

    /**
     * @return string
     */
    private function performPopupAction(): string
    {
        $blockId = $this->getData('block_id');
        return "powerToysOpenModal('$blockId')";
    }
}
