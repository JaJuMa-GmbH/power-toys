<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023-present JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */
declare(strict_types=1);

namespace Jajuma\PowerToys\Api\Data;

interface BookMarkSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get BookMark list.
     * @return \Jajuma\PowerToys\Api\Data\BookMarkInterface[]
     */
    public function getItems();

    /**
     * Set status list.
     * @param \Jajuma\PowerToys\Api\Data\BookMarkInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

