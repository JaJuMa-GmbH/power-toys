<?php
namespace Jajuma\PowerToys\Model;

use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Authorization;
use Magento\Framework\Authorization\PolicyInterface;
use Magento\Framework\Math\Random;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\User\Model\UserFactory;

/**
 * Class Cookie
 */
class Auth
{
    const COOKIE_LIFETIME_CONFIG_PATH = 'power_toys/general/cookie_lifetime';

    const DEFAULT_COOKIE_LIFE_TIME = 86400;
    const GLOBAL_ACL = 'Jajuma_PowerToys::function';
    const AUTH_COOKIE_NAME = 'powertoys_auth_cookie';

    const AUTH_COOKIE_2_NAME = 'powertoys_auth';

    const AUTH_CACHE_NAME = 'powertoys_auth_cache';

    const CACHE_TAG = 'powertoys';

    /**
     * @var CookieManagerInterface CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var CookieMetadataFactory CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    protected $authSession;

    protected $mathRandom;

    protected $cache;

    protected $aclPolicy;

    protected $resourceConnection;

    protected $userFactory;

    protected $scopeConfig;

    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        Session $authSession,
        Random $mathRandom,
        CacheInterface $cache,
        PolicyInterface $aclPolicy,
        ResourceConnection $resourceConnection,
        UserFactory $userFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->authSession = $authSession;
        $this->mathRandom = $mathRandom;
        $this->cache = $cache;
        $this->aclPolicy = $aclPolicy;
        $this->resourceConnection = $resourceConnection;
        $this->userFactory = $userFactory;
        $this->scopeConfig = $scopeConfig;
    }

    public function isAllowedPowerToys() {
        $adminLoggedId = $this->isAdminLoggedIn();
        if ($adminLoggedId) {
            if ($this->validateAdminPermission($adminLoggedId, self::GLOBAL_ACL)){
                //Expire time by second
                $expireTime = $this->getLifeTime();

                //Jacob: Issue when multiple admin logged in
                //If admin logged in , try to check auth str first
                $authData = $this->isHaveAuthStr($adminLoggedId);
                $authStr = null;
                if ($authData) {
                    //Prevent issue when multiple admin logged to save account (multiple admin logged = true)
                    //They should have the same auth str (value already saved in DB)
                    //If have data check expire time and return auth string from DB
                    $expiredAt = $authData['expire_at'] ?? 0;
                    $curUnixTime = time();
                    if ($curUnixTime < $expiredAt) {
                        $authStr = $authData['auth_token'];
                        //New Expire time for saving to cookie
                        $expireTime = $expiredAt - $curUnixTime;
                    }
                }

                //If cannot find auth data from DB, or auth str was expire, try to generate a new one and save to DB
                if (!$authStr) {
                    $authStr = $this->mathRandom->getRandomString('32', Random::CHARS_LOWERS.Random::CHARS_UPPERS.Random::CHARS_DIGITS);
                    $authStr .= "user_id_$adminLoggedId";
                    $authStr = hash('md5', $authStr);
                    $authStr = strtoupper($authStr);
                    $this->saveAuthDBData($authStr, $adminLoggedId);
                }
                //Save new Auth cookie
                $this->setAuthCookie($authStr, $expireTime);

                //AuthStr 2 can be ramdomly here
                $authStr2 = $this->mathRandom->getRandomString('32', Random::CHARS_LOWERS.Random::CHARS_UPPERS.Random::CHARS_DIGITS);
                $authStr2 = strtoupper($authStr2);
                $this->setAuthCookie2($authStr2, $expireTime);
                return true;
            }
            //Delete all auth cookie if admin logged but dont have the permission, ;)
            $this->deleteCookie(self::AUTH_COOKIE_NAME);
            $this->deleteCookie(self::AUTH_COOKIE_2_NAME);
        }
        //Check for frontend or magewire request with cookie
        if ($this->isAllowedFromFrontend()) {
            return true;
        }
        return false;
    }

    public function validateAdminAcl($aclRole) {
        $adminUserId = $this->isAdminLoggedIn();
        if (!$adminUserId) {
            $authStr = $this->getAuthCookie();
            $authData = $this->getAuthDBData($authStr);
            $adminUserId = $authData['admin_user_id'] ?? 0;
        }
        if ($adminUserId) {
            return $this->validateAdminPermission($adminUserId, $aclRole);
        } else {
            return false;
        }
    }

    private function validateAdminPermission($adminUserId, $aclRole) {
        //Get role id from admin user here
        $adminUser = $this->userFactory->create()->load($adminUserId);
        $roleId = $adminUser->getAclRole();
        return $this->aclPolicy->isAllowed($roleId, $aclRole);
    }

    private function isAllowedFromFrontend() {
        $authStr = $this->getAuthCookie();
        $authData = $this->getAuthDBData($authStr);
        $expireTime = $authData['expire_at'] ?? 0;
        $adminUserId = $authData['admin_user_id'] ?? 0;
        $curUnixTime = time();
        if ($curUnixTime < $expireTime) {
            if ($this->validateAdminPermission($adminUserId, self::GLOBAL_ACL)) {
                return true;
            }
        }
        return false;
    }

    private function isHaveAuthStr($userId) {
        $authTable = $this->resourceConnection->getTableName('jajuma_powertoys_auth');
        $data = $this->resourceConnection->getConnection()
            ->select()->from($authTable)->where('admin_user_id = ?', (string)$userId);
        return $data->query()->fetch();
    }

    private function getAuthDBData($authStr) {
        $authTable = $this->resourceConnection->getTableName('jajuma_powertoys_auth');
        $data = $this->resourceConnection->getConnection()
            ->select()->from($authTable)->where('auth_token = ?', (string)$authStr);
        return $data->query()->fetch();
    }
    private function saveAuthDBData($authStr, $userId) {
        $curTime = time();
        $expireAt = $this->getLifeTime() + $curTime;
        $authTable = $this->resourceConnection->getTableName('jajuma_powertoys_auth');
        $this->resourceConnection->getConnection()->insertOnDuplicate(
            $authTable,
            [
                'admin_user_id' => $userId,
                'auth_token' => $authStr,
                'expire_at' => $expireAt
            ]
        );
    }

    private function isAdminLoggedIn() {
        $admin = $this->authSession->getUser();
        if ($admin && $admin->getUserId()) {
            return $admin->getUserId();
        }
        return false;
    }

    private function getLifeTime() {
        $cookieLifeTime = $this->scopeConfig->getValue(self::COOKIE_LIFETIME_CONFIG_PATH);
        if ($cookieLifeTime) {
            return intval($this->scopeConfig->getValue(self::COOKIE_LIFETIME_CONFIG_PATH));
        }
        return self::DEFAULT_COOKIE_LIFE_TIME;
    }
    private function setAuthCookie($cookieValue, $lifetime = self::DEFAULT_COOKIE_LIFE_TIME)
    {
        $publicCookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata();
        $publicCookieMetadata->setDuration($lifetime);
        $publicCookieMetadata->setPath('/');
        $publicCookieMetadata->setHttpOnly(true);

        $this->cookieManager->setPublicCookie(
            self::AUTH_COOKIE_NAME,
            $cookieValue,
            $publicCookieMetadata
        );
    }

    private function setAuthCookie2($cookieValue, $lifetime = self::DEFAULT_COOKIE_LIFE_TIME)
    {
        $publicCookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata();
        $publicCookieMetadata->setDuration($lifetime);
        $publicCookieMetadata->setPath('/');
        $publicCookieMetadata->setHttpOnly(false);

        $this->cookieManager->setPublicCookie(
            self::AUTH_COOKIE_2_NAME,
            $cookieValue,
            $publicCookieMetadata
        );
    }

    private function getAuthCookie()
    {
        return $this->cookieManager->getCookie(self::AUTH_COOKIE_NAME);
    }

    public function deleteCookie($cookieName) {
        $publicCookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata();
        $publicCookieMetadata->setDuration(0);
        $publicCookieMetadata->setPath('/');
        $this->cookieManager->deleteCookie($cookieName, $publicCookieMetadata);
    }
}
