<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023-present JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */
declare(strict_types=1);

namespace Jajuma\PowerToys\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface BookMarkRepositoryInterface
{

    /**
     * Save BookMark
     * @param \Jajuma\PowerToys\Api\Data\BookMarkInterface $bookMark
     * @return \Jajuma\PowerToys\Api\Data\BookMarkInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Jajuma\PowerToys\Api\Data\BookMarkInterface $bookMark
    );

    /**
     * Retrieve BookMark
     * @param string $bookmarkId
     * @return \Jajuma\PowerToys\Api\Data\BookMarkInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($bookmarkId);

    /**
     * Retrieve BookMark matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Jajuma\PowerToys\Api\Data\BookMarkSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete BookMark
     * @param \Jajuma\PowerToys\Api\Data\BookMarkInterface $bookMark
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Jajuma\PowerToys\Api\Data\BookMarkInterface $bookMark
    );

    /**
     * Delete BookMark by ID
     * @param string $bookmarkId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($bookmarkId);
}

