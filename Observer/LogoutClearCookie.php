<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Jajuma\PowerToys\Observer;

use Jajuma\PowerToys\Model\Auth;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;

class LogoutClearCookie implements ObserverInterface
{
    private $powerToysAuth;

    public function __construct(Auth $powerToysAuth) {
        $this->powerToysAuth = $powerToysAuth;
    }
    /**
     * @param EventObserver $observer
     *
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        try {
            $this->powerToysAuth->deleteCookie(Auth::AUTH_COOKIE_NAME);
            $this->powerToysAuth->deleteCookie(Auth::AUTH_COOKIE_2_NAME);
        } catch (\Exception $e){}
    }
}
