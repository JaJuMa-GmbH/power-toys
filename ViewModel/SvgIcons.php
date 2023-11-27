<?php
/**
 * Hyvä Themes - https://hyva.io
 * Copyright © Hyvä Themes 2020-present. All rights reserved.
 * This product is licensed per Magento install
 * See https://hyva.io/license
 */

declare(strict_types=1);

namespace Jajuma\PowerToys\ViewModel;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\View\Asset;
use Magento\Framework\View\DesignInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

// phpcs:disable Magento2.Functions.DiscouragedFunction.Discouraged
// phpcs:disable Magento2.Functions.StaticFunction.StaticFunction

/**
 * This generic SvgIcons view model can be used to render any icon set (i.e. subdirectory in web/svg).
 *
 * It is used as part of the Jajuma_PowerToys module to provide the Heroicon view models, and to provide access to
 * custom SVG icons inside Hyvä themes.
 * This class is also intended to be used as a base class for other modules providing icon sets to Hyvä themes.
 *
 * Possible di.xml customization points:
 *
 * iconPathPrefix (default: Jajuma_PowerToys::svg)
 *
 * The iconPathPrefix will be prepended before the specified icon.
 * For example, given an iconPrefix of `My_Module::svg`, a call to `renderHtml('my-icon') renders the first existing of
 *
 * 1. <current-theme>/My_Module/web/svg/my-icon.svg
 * 2. My_Module/view/frontend/web/svg/my-icon.svg
 * 2. My_Module/view/base/web/svg/my-icon.svg
 * 3. <current-theme>/web/svg/my-icon.svg
 *
 * It is possible to specify subdirectories as part of the iconPathPrefix, as in `My_Module::svg/light`.
 *
 * For an example, please refer to the constructor arguments configured for Hyva\Theme\ViewModel\HeroiconsSolid in
 * the file hyva-themes/magento2-theme-module/etc/frontend/di.xml
 *
 * The Icon View Models included within the hyva_Theme module use the following iconPathPrefixes:
 * - SvgIcons: Jajuma_PowerToys::svg
 * - HeroiconsSolid: Jajuma_PowerToys::svg/heroicons/solid
 * - HeroiconsOutline: Jajuma_PowerToys::svg/heroicons/outline
 *
 * pathPrefixMapping (optional)
 *
 * The pathPrefixMapping allows modules to register an alias for their "iconPathPrefix".
 * This is useful for providing more readable icon names to the CMS content template processor.
 * For example, the icon {{icon "sport-icons/outline/darts"}} can be mapped to the icon
 * asset path "Hyva_SportIcons::svg/outline/darts".
 *
 * The pathPrefixMapping is applied by using the first part of the icon path ("sport-icons" in the example above)
 * as the map lookup key. If a value is present, it will be used. If no value is present in the map,
 * the iconPathPrefix is used (see above).
 *
 *
 * iconSet (optional)
 *
 * If set, the iconSet is injected between the path prefix and the remainder of the icon path.
 * For example, assuming the iconPathPrefix is `My_Module::svg/awesome` and the iconSet is 'outline', then
 * calling $icon->renderHtml('box') would render `My_Module::svg/awwesome/outline/box`.
 *
 * Note: there is some duplication in functionality between the iconPathPrefix and the iconSet.
 * In the previous example an iconPathPrefix of `My_Module::svg/awesome/outline` could also be used.
 * The reason this overlap of functionality is present is to preserve backward compatibility.
 *
 * The hyva-themes/magento2-theme-module ships with two Heroicon iconsets and matching view models:
 *
 * @see HeroiconsSolid
 * @see HeroiconsOutline
 */
class SvgIcons implements ArgumentInterface
{

    public const CACHE_TAG = 'JAJUMA_POWERTOYS_ICONS';

    /**
     * Module name prefix for icon asset, e.g. Jajuma_PowerToys::svg
     *
     * @var string
     */
    private $iconPathPrefix;

    /**
     * Human friendly alias for iconPathPrefixes
     *
     * @var string[]
     */
    private $pathPrefixMapping;

    /**
     * Optional folder name to be appended to the $iconPathPrefix
     *
     * @var string
     */
    private $iconSet = '';

    /**
     * @var Asset\Repository
     */
    private $assetRepository;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var DesignInterface
     */
    private $design;

    public function __construct(
        Asset\Repository $assetRepository,
        CacheInterface $cache,
        DesignInterface $design,
        string $iconPathPrefix = 'Jajuma_PowerToys::svg',
        string $iconSet = '',
        array $pathPrefixMapping = []
    ) {
        $this->assetRepository   = $assetRepository;
        $this->cache             = $cache;
        $this->design            = $design;
        $this->iconPathPrefix    = rtrim($iconPathPrefix, '/');
        $this->iconSet           = $iconSet;
        $this->pathPrefixMapping = $pathPrefixMapping;
    }

    /**
     * Renders an inline SVG icon from the configured icon set
     *
     * The method ends with Html instead of Svg so that the Magento code sniffer understands it is safe HTML and does
     * not need to be escaped.
     *
     * @param string $icon The SVG file name without .svg suffix
     * @param string $classNames CSS classes to add to the root element, space separated
     * @param int|null $width Width in px (recommended to render in correct size without CSS)
     * @param int|null $height Height in px (recommended to render in correct size without CSS)
     * @param array $attributes Additional attributes you can set on the SVG as key => value, like :class for AlpineJS
     * @return string
     */
    public function renderHtml(
        string $icon,
        string $classNames = '',
        ?int $width = 24,
        ?int $height = 24,
        array $attributes = []
    ): string {
        $iconPath = $this->applyPathPrefixAndIconSet($icon);

        $cacheKey = $this->design->getDesignTheme()->getCode() .
            '/' . $iconPath .
            '/' . $classNames .
            '#' . $width .
            '#' . $height .
            '#' . hash('md5', json_encode($attributes));

        if ($result = $this->cache->load($cacheKey)) {
            return $result;
        }

        try {
            $rawIconSvg = \file_get_contents($this->getFilePath($iconPath)); // phpcs:disable
            $result     = $this->applySvgArguments($rawIconSvg, $classNames, $width, $height, $attributes);

            $this->cache->save($result, $cacheKey, [self::CACHE_TAG]);

            return $result;
        } catch (Asset\File\NotFoundException $exception) {
            $error = (string) __('Unable to find the SVG icon "%1', $icon);
            throw new Asset\File\NotFoundException($error, 0, $exception);
        }
    }

    /**
     * Magic method to allow iconNameHtml() instead of renderHtml('icon-name'). Subclasses may
     * use `@method` doc blocks to provide autocompletion for available icons.
     */
    public function __call($method, $args)
    {
        if (\preg_match('/^(.*)Html$/', $method, $matches)) {
            return $this->renderHtml(self::camelCaseToKebabCase($matches[1]), ...$args);
        }
        return '';
    }

    /**
     * Convert a CamelCase string into kebab-case
     *
     * For example ArrowUp => arrow-up
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    private static function camelCaseToKebabCase(string $str): string
    {
        return strtolower(preg_replace('/(.|[0-9])([A-Z]|[0-9])/', "$1-$2", $str));
    }

    /**
     * Return absolute path to icon file, respecting theme fallback.
     *
     * If no matching icon within the given iconPathPrefix module is found, it will fall back to the theme web folder.
     */
    private function getFilePath(string $iconPath): string
    {
        $asset = $this->assetRepository->createAsset($iconPath);
        try {
            // try to locate asset with iconPathPrefix (e.g. Jajuma_PowerToys::svg)
            $path = $asset->getSourceFile();
        } catch (Asset\File\NotFoundException $exception) {
            // fallback to web/ folder in current theme if not found
            $path = $this->assetRepository->createAsset($asset->getFilePath())->getSourceFile();
        }
        return $path;
    }

    private function applyPathPrefixAndIconSet(string $icon): string
    {
        $iconSet       = $this->iconSet ? $this->iconSet . '/' : '';
        $iconPathParts = explode('/', $icon, 2);
        $this->iconPathPrefix = 'Jajuma_PowerToys::svg/heroicons/outline';
        return count($iconPathParts) === 2 && isset($this->pathPrefixMapping[$iconPathParts[0]])
            ? $this->pathPrefixMapping[$iconPathParts[0]] . '/' . $iconSet . $iconPathParts[1] . '.svg'
            : $this->iconPathPrefix . '/' . $iconSet . $icon . '.svg';
    }

    private function applySvgArguments(
        string $origSvg,
        string $classNames,
        ?int $width,
        ?int $height,
        array $attributes
    ): string {
        $svgXml = new \SimpleXMLElement($origSvg);
        if (trim($classNames)) {
            $svgXml['class'] = $classNames;
        }
        if ($width) {
            $svgXml['width'] = (string) $width;
        }
        if ($height) {
            $svgXml['height'] = (string) $height;
        }

        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                if (!empty($key) && !isset($svgXml[strtolower($key)])) {
                    $svgXml[strtolower($key)] = (string) $value;
                }
            }
        }

        return \str_replace("<?xml version=\"1.0\"?>\n", '', $svgXml->asXML());
    }
}
