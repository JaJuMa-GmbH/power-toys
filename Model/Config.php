<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Jajuma\PowerToys\Model;

/**
 * Class Config
 */
class Config
{
    protected $configData;

    public function __construct(
        Config\Data $configData
    )
    {
        $this->configData = $configData;
    }

    public function getWidget($path = null)
    {
        return $this->configData->get($path, []);
    }
}
