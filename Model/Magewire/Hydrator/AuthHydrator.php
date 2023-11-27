<?php declare(strict_types=1);

namespace Jajuma\PowerToys\Model\Magewire\Hydrator;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magewirephp\Magewire\Component;
use Magewirephp\Magewire\Model\HydratorInterface;
use Magewirephp\Magewire\Model\RequestInterface;
use Magewirephp\Magewire\Model\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthHydrator implements HydratorInterface
{
    private $powerToysHelper;

    private $auth;

    public function __construct() {
        if (!$this->powerToysHelper) {
            $this->powerToysHelper = ObjectManager::getInstance()->create('Jajuma\PowerToys\Helper\Data');
        }
        if (!$this->auth) {
            $this->auth = ObjectManager::getInstance()->create('Jajuma\PowerToys\Model\Auth');
        }
    }

    public function hydrate(Component $component, RequestInterface $request): void
    {
        $componentIdArr = $this->powerToysHelper->getMagewireComponentIdArr();
        $componentId = $component->id;
        if (in_array($componentId, $componentIdArr)) {
            //Is magewire component of power toys
            //Check Auth
            if (!$this->auth->isAllowedPowerToys()) {
                //throw new HttpException(403, 'Method Not Allowed');
            }
        }
        // Subsequent hydration request
        if ($request->isSubsequent()) {}

        // Initial page load hydration request
        if ($request->isPreceding()) {}
    }

    public function dehydrate(Component $component, ResponseInterface $response): void
    {
        // Subsequent hydration request
        if ($response->getRequest()->isSubsequent()) {}

        // Initial page load hydration request
        if ($response->getRequest()->isPreceding()) {}
    }
}
