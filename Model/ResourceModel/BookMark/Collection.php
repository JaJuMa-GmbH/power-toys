<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */
declare(strict_types=1);

namespace Jajuma\PowerToys\Model\ResourceModel\BookMark;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'bookmark_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Jajuma\PowerToys\Model\BookMark::class,
            \Jajuma\PowerToys\Model\ResourceModel\BookMark::class
        );
    }
}

