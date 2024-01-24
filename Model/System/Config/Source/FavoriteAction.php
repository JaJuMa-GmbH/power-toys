<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Jajuma\PowerToys\Model\System\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Jajuma\PowerToys\Model\Config;

/**
 * Class FavoriteAction
 * @package Jajuma\PowerToys\Model\System\Config\Source
 */
class FavoriteAction implements OptionSourceInterface
{
    /**
     * @var Config
     */
    protected $powerToysConfig;

    /**
     * @param Config $powerToysConfig
     */
    public function __construct(
        Config $powerToysConfig
    ) {
        $this->powerToysConfig = $powerToysConfig;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->toArray() as $value => $label) {
            $options[] = ["value" => $value, "label" => $label];
        }

        return $options;
    }

    /**
     * Get options in "key=>value" format.
     *
     * @return array
     */
    public function toArray()
    {
        $quickActions = $this->powerToysConfig->getWidget('quickaction');
        $array = [];
        foreach ($quickActions as $actionId => $quickAction) {
            if ($actionId !== 'example_quickaction') {
                $array[$actionId] = $quickAction['name'];
            }
        }

        return $array;
    }
}
