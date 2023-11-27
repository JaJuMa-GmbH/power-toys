<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023-present JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

declare(strict_types=1);

namespace Jajuma\PowerToys\Controller\Adminhtml\Session;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;

class Check extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Context
     */
    protected $context;

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $result = $this->resultFactory->create('json');
        if ($this->_auth->isLoggedIn()) {
            $result->setData([
                'success' => true
            ]);
            $result->setHttpResponseCode(200);
            return $result;
        } else {
            $result->setData([
                'success' => false
            ]);
            $result->setHttpResponseCode(403);
        }
        $result->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
        return $result;
    }
}
