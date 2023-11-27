<?php
/**
 * @author    JaJuMa GmbH <info@jajuma.de>
 * @copyright Copyright (c) 2023 JaJuMa GmbH <https://www.jajuma.de>. All rights reserved.
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Jajuma\PowerToys\Model\Config;

use Magento\Framework\Config\ConverterInterface;

class Converter implements ConverterInterface
{
    const DEFAULT_WIDGET_CLASS = [
        '/powertoys/dashboard' => 'Jajuma\PowerToys\Block\PowerToys\Dashboard',
        '/powertoys/quickaction' => 'Jajuma\PowerToys\Block\PowerToys\QuickAction'
    ];

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * phpcs:disable Generic.Metrics.NestingLevel
     */
    public function convert($source)
    {
        $dataReturn = [];
        $xpath = new \DOMXPath($source);
        /** @var $widget \DOMNode */
        $dataReturn['dashboard'] = $this->collectXml('/powertoys/dashboard', $xpath);
        $dataReturn['quickaction'] = $this->collectXml('/powertoys/quickaction', $xpath);
        return $dataReturn;
    }

    private function collectXml($typeName, $xpath) {
        $widgets = [];
        foreach ($xpath->query($typeName) as $widget) {
            $widgetAttributes = $widget->attributes;
            $widgetClass  = $widgetAttributes->getNamedItem('class');
            $widgetArray = ['@' => []];
            if ($widgetClass) {
                $widgetArray['@']['type'] = $widgetAttributes->getNamedItem('class')->nodeValue;
            } else {
                $widgetArray['@']['type'] = self::DEFAULT_WIDGET_CLASS[$typeName];
            }

            $widgetId = $widgetAttributes->getNamedItem('id');
            if (!$widgetId) {
                continue;
            }
            /** @var $widgetSubNode \DOMNode */
            foreach ($widget->childNodes as $widgetSubNode) {
                switch ($widgetSubNode->nodeName) {
                    case 'label':
                        $widgetArray['name'] = $widgetSubNode->nodeValue;
                        break;
                    case 'description':
                        $widgetArray['description'] = $widgetSubNode->nodeValue;
                        break;
                    case 'arguments':
                        /** @var $arguments \DOMNode */
                        foreach ($widgetSubNode->childNodes as $argument) {
                            if ($argument->nodeName === '#text' || $argument->nodeName === '#comment') {
                                continue;
                            }
                            $subNodeAttributes = $argument->attributes;
                            $argumentName = $subNodeAttributes->getNamedItem('name')->nodeValue;
                            $widgetArray['arguments'][$argumentName] = $argument->nodeValue;
                        }
                        break;
                    case '#comment':
                    case "#text":
                        break;
                    default:
                        throw new \LogicException(
                            sprintf(
                                "Unsupported child xml node '%s' found in the 'widget' node",
                                $widgetSubNode->nodeName
                            )
                        );
                }
            }
            $widgets[$widgetId->nodeValue] = $widgetArray;
        }
        return $widgets;
    }
}
