<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */
declare(strict_types=1);

namespace Jajuma\PowerToys\Model;

use Jajuma\PowerToys\Api\Data\BookMarkInterface;
use Magento\Framework\Model\AbstractModel;

class BookMark extends AbstractModel implements BookMarkInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Jajuma\PowerToys\Model\ResourceModel\BookMark::class);
    }

    /**
     * @inheritDoc
     */
    public function getBookmarkId()
    {
        return $this->getData(self::BOOKMARK_ID);
    }

    /**
     * @inheritDoc
     */
    public function setBookmarkId($bookmarkId)
    {
        return $this->setData(self::BOOKMARK_ID, $bookmarkId);
    }
    
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * @inheritDoc
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritDoc
     */
    public function getIconSvg()
    {
        return $this->getData(self::ICONSVG);
    }

    /**
     * @inheritDoc
     */
    public function setIconSvg($iconsvg)
    {
        return $this->setData(self::ICONSVG, $iconsvg);
    }

    /**
     * @inheritDoc
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    /**
     * @inheritDoc
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }
}

