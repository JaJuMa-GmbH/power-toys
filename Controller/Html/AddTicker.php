<?php
declare(strict_types=1);

namespace Jajuma\PowerToys\Controller\Html;

use Jajuma\PowerToys\Helper\Data;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerialize;

class AddTicker implements ActionInterface
{
    const TICKER_URL = 'https://www.jajuma.de/pot-ticker.html';

    private $resultFactory;

    private $helper;

    /**
     * @param Context $context
     * @param Data $helper
     * @param JsonSerialize $jsonSerialize
     */
    public function __construct(
        Context $context,
        Data $helper,
        JsonSerialize $jsonSerialize
    ) {
        $this->resultFactory = $context->getResultFactory();
        $this->helper = $helper;
    }

    /**
     * Get html
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $result = $this->resultFactory->create('json');
        $html = '';
        if ($this->helper->isAllowedPowerToys()) {
            $url = self::TICKER_URL;
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_REFERER, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $html = curl_exec($ch);
                $html = htmlspecialchars_decode($html);
                curl_close($ch);
            } catch (\Exception $e) {
                $html = '';
            }
        }

        $result->setData([
            'html' => $html
        ]);

        return $result;
    }
}
