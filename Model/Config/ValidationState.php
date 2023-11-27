<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Jajuma\PowerToys\Model\Config;

use Magento\Framework\Config\ValidationStateInterface;

class ValidationState implements ValidationStateInterface
{
    public function isValidationRequired()
    {
        return false;
    }
}
