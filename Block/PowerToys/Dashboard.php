<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Jajuma\PowerToys\Block\PowerToys;

use Magento\Framework\View\Element\Template;

/**
 * Default block for Widget
 * This class can be extendable
 */
class Dashboard extends Template
{
    protected $_template = 'Jajuma_PowerToys::default_templates/dashboard.phtml';

    public function isEnable(): bool
    {
        return $this->getData('enable') == 'true';
    }

    public function isLazyload(): bool {
        return $this->getData('lazyload') == 'true';
    }

    public function getName(): string {
        return $this->getData('name');
    }
}
