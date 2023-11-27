<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023-present JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */
declare(strict_types=1);

namespace Jajuma\PowerToys\Api\Data;

interface BookMarkInterface
{

    const URL = 'url';
    const NAME = 'name';
    const BOOKMARK_ID = 'bookmark_id';
    const POSITION = 'position';
    const ICONSVG = 'icon_svg';

    /**
     * Get bookmark_id
     * @return string|null
     */
    public function getBookmarkId();

    /**
     * Set bookmark_id
     * @param string $bookmarkId
     * @return \Jajuma\PowerToys\BookMark\Api\Data\BookMarkInterface
     */
    public function setBookmarkId($bookmarkId);


    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Jajuma\PowerToys\BookMark\Api\Data\BookMarkInterface
     */
    public function setName($name);

    /**
     * Get url
     * @return string|null
     */
    public function getUrl();

    /**
     * Set url
     * @param string $url
     * @return \Jajuma\PowerToys\BookMark\Api\Data\BookMarkInterface
     */
    public function setUrl($url);

    /**
     * Set iconsvg
     * @param string $iconsvg
     * @return \Jajuma\PowerToys\BookMark\Api\Data\BookMarkInterface
     */
    public function setIconSvg($iconsvg);

    /**
     * Get iconsvg
     * @return string|null
     */
    public function getIconSvg();

    /**
     * Get position
     * @return string|null
     */
    public function getPosition();

    /**
     * Set position
     * @param string $position
     * @return \Jajuma\PowerToys\BookMark\Api\Data\BookMarkInterface
     */
    public function setPosition($position);
}

