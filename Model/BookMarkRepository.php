<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */
declare(strict_types=1);

namespace Jajuma\PowerToys\Model;

use Jajuma\PowerToys\Api\BookMarkRepositoryInterface;
use Jajuma\PowerToys\Api\Data\BookMarkInterface;
use Jajuma\PowerToys\Api\Data\BookMarkInterfaceFactory;
use Jajuma\PowerToys\Api\Data\BookMarkSearchResultsInterfaceFactory;
use Jajuma\PowerToys\Model\ResourceModel\BookMark as ResourceBookMark;
use Jajuma\PowerToys\Model\ResourceModel\BookMark\CollectionFactory as BookMarkCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class BookMarkRepository implements BookMarkRepositoryInterface
{

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ResourceBookMark
     */
    protected $resource;

    /**
     * @var BookMarkCollectionFactory
     */
    protected $bookMarkCollectionFactory;

    /**
     * @var BookMark
     */
    protected $searchResultsFactory;

    /**
     * @var BookMarkInterfaceFactory
     */
    protected $bookMarkFactory;


    /**
     * @param ResourceBookMark $resource
     * @param BookMarkInterfaceFactory $bookMarkFactory
     * @param BookMarkCollectionFactory $bookMarkCollectionFactory
     * @param BookMarkSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceBookMark $resource,
        BookMarkInterfaceFactory $bookMarkFactory,
        BookMarkCollectionFactory $bookMarkCollectionFactory,
        BookMarkSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->bookMarkFactory = $bookMarkFactory;
        $this->bookMarkCollectionFactory = $bookMarkCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(BookMarkInterface $bookMark)
    {
        try {
            $this->resource->save($bookMark);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the bookMark: %1',
                $exception->getMessage()
            ));
        }
        return $bookMark;
    }

    /**
     * @inheritDoc
     */
    public function get($bookMarkId)
    {
        $bookMark = $this->bookMarkFactory->create();
        $this->resource->load($bookMark, $bookMarkId);
        if (!$bookMark->getId()) {
            throw new NoSuchEntityException(__('BookMark with id "%1" does not exist.', $bookMarkId));
        }
        return $bookMark;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->bookMarkCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(BookMarkInterface $bookMark)
    {
        try {
            $bookMarkModel = $this->bookMarkFactory->create();
            $this->resource->load($bookMarkModel, $bookMark->getBookmarkId());
            $this->resource->delete($bookMarkModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the BookMark: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($bookMarkId)
    {
        return $this->delete($this->get($bookMarkId));
    }
}

