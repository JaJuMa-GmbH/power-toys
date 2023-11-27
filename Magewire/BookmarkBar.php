<?php declare(strict_types=1);
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Jajuma\PowerToys\Magewire;

use Magento\Framework\App\ObjectManager;
use Magewirephp\Magewire\Component;
use Rakit\Validation\Validator;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\App\ResourceConnection;
class BookmarkBar extends \Magewirephp\Magewire\Component\Form
{
    const TABLE_BOOKMARK = 'jajuma_powertoys_bookmark';

    public $bookmarkItems = [];

    public $bookmark = [];

    public $fileIcons = [];

    public $rules = [
        'bookmark.name' => 'required',
        'bookmark.url' => 'required'
    ];

    protected $messages = [
        'bookmark.name:required' => 'The "Name" property can\'t be empty',
        'bookmark.url:required' => 'The "Url" property can\'t be empty',
    ];

    private $dir;

    private $resourceConnection;

    public function __construct(
        Reader $dir,
        Validator $validator,
        ResourceConnection $resourceConnection
    ) {
        $this->dir = $dir;
        $this->resourceConnection = $resourceConnection;
        parent::__construct($validator);
    }

    public function mount(): void
    {
        $this->fetchBookmarks();
        $this->getAllFileIcon();
        parent::mount();
    }

    public function saveBookmark() {
        //validate data
        try {
            $this->validate();
        } catch (\Magewirephp\Magewire\Exception\ValidationException $exception) {
            foreach ($this->getErrors() as $error) {
                $this->dispatchErrorMessage($error);
            }
        }
        //save bookmark to db
        $data = [
            'name' => $this->bookmark['name'],
            'url' => $this->bookmark['url'],
            'icon_svg' => isset($this->bookmark['icon_svg']) ? $this->bookmark['icon_svg'] : '',
            'position' => count($this->bookmarkItems) + 1
        ];
        if (isset($this->bookmark['bookmark_id']) && $this->bookmark['bookmark_id']) {
            $data['bookmark_id'] = $this->bookmark['bookmark_id'];
            unset($data['position']);
        }
        $this->saveBookmarkData($data);
        //reset bookmark
        $this->bookmark = [];
        $this->dispatchBrowserEvent('close-bookmark-modal');
        //refresh bookmark items
        $this->fetchBookmarks();
    }

    private function saveBookmarkData($data)
    {
        try {
            $connection= $this->resourceConnection->getConnection();
            $bookmarkTable = $this->resourceConnection->getTableName(self::TABLE_BOOKMARK);
            $connection->insertOnDuplicate($bookmarkTable, $data);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function deleteBookmark() {
        if (isset($this->bookmark['bookmark_id'])) {
            $connection= $this->resourceConnection->getConnection();
            $bookmarkTable = $this->resourceConnection->getTableName(self::TABLE_BOOKMARK);
            $query = "DELETE FROM `" . $bookmarkTable .  "` WHERE `bookmark_id` = '" . $this->bookmark['bookmark_id'] . "'";
            $connection->query($query);
        }
        //reset bookmark
        $this->bookmark = [];
        $this->dispatchBrowserEvent('close-bookmark-modal');
        //refresh bookmark items
        $this->fetchBookmarks();
    }

    public function initBookmark()
    {
        $this->bookmark = [];
        $this->bookmark['position'] = '10';
    }

    private function fetchBookmarks() {
        //Init dummy bookmark, getting from collection here
        $connection = $this->resourceConnection->getConnection();
        $bookmarkTable = $this->resourceConnection->getTableName(self::TABLE_BOOKMARK);
        $query = "SELECT * FROM `" . $bookmarkTable . "` ORDER BY position ASC";
        $result = $connection->fetchAll($query);
        $this->bookmarkItems = $result;
    }

    private function getAllFileIcon()
    {
        $viewDir = $this->dir->getModuleDir(
            \Magento\Framework\Module\Dir::MODULE_VIEW_DIR,
            'Jajuma_PowerToys'
        );
        $outLineHeroIconsDir = $viewDir . '/base/web/svg/heroicons/outline';
        $files = scandir($outLineHeroIconsDir);
        unset($files[0]);
        unset($files[1]);
        $this->fileIcons = $files;
    }
}
