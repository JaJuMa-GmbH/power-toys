# :jigsaw: Power Toys for Magento 2 by [JaJuMa](https://www.jajuma.de/)

<img src="https://www.jajuma.de/media/wysiwyg/jajuma-develop/power-toys-magento/JaJuMa-Power-Toys-for-Magento-Banner.jpg">  


<img align="right" width="35%" height="auto" src="https://www.jajuma.de/media/wysiwyg/jajuma-develop/power-toys-magento/JaJuMa-Power-Toys-For-Magento-2-small.png">  
Power Toys for Magento 2 by [JaJuMa](https://www.jajuma.de/en) is a framework
for tools and helpers, a.k.a "Toys", for Magento Admins to tune and streamline their Magento experience for greater productivity.

## Compatible with  

<td>
    <table>
     <tr>
        <td><b>Hyvä Themes</b></td>
        <td><b>Mage-OS</b></td>
        <td><b>Magento</b></td>
     </tr>
     <tr>
       <td><a href="https://www.jajuma.de/en/jajuma-shop/online-shop-with-magento-2-and-hyva-themes"><img align="left" width="80" src="https://www.jajuma.de/media/wysiwyg/jajuma-shop/magento-with-hyva/JaJuMa-Hyvanaut-small.png"></a></td>
       <td><a href="https://www.jajuma.de/en/jajuma-shop/demo-shop-with-mage-os-and-hyva-themes"><img align="left" width="80" src="https://www.jajuma.de/media/wysiwyg/jajuma-develop/Mage-OS-Compatible.svg"></a></td>
       <td><a href="https://www.jajuma.de/en/jajuma-shop"><img align="left" height="60" src="https://www.jajuma.de/media/wysiwyg/jajuma-develop/magento-icon.svg"></a></td>
    </tr>
    </table>
</td>

## Features

This extension provides the core framework for JaJuMa Power Toys:
* :on: Power Toys are available in Magento Backend and Frontend (while logged in as Admin only)
* :record_button: "Assistive Touch" inspired floating button to show/hide the Power Toys panel.   
Can be easily positioned by drag'n'drop on left or right edge of the screen without overlapping other UI elements.
* :window: Power Toys panel
  * :arrows_clockwise: has a sort mode for sorting toys by drag'n'drop
  * :last_quarter_moon: comes with light mode and dark mode
  * :jigsaw: Supports 3 types of toys:
    * :magic_wand: Quick Actions Toys - Allow performing Magento admin actions or provide additional inputs and features via popup by simple click on a button  
      (each as separate module - see list of available toys below)
    * :chart_with_upwards_trend: Dashboard Toys - Display information and KPIs collected from Magento or APIs  
      (each as separate module - see list of available toys below)
    * :bookmark: Bookmark Toy - Allow to set bookmarks to frequently used Magento Backend/Frontend URLs   
      (included in this module)
* :rocket: Build with performance in mind, avoiding negative impact on page load times as much as possible
* :man_technologist: Build with developers in mind, create your own toys with ease...

## Screenshots

<td>
    <table>
     <tr>
        <td><b>Power Toys Panel</b><br>Dark Mode</td>
        <td><b>Power Toys Panel</b><br>Light Mode</td>
     </tr>
     <tr>
       <td><img src="https://www.jajuma.de/media/wysiwyg/jajuma-develop/power-toys-magento/screenshots/Magento-Power-Toys-Core-Blank-Dark.png"></td>
       <td><img src="https://www.jajuma.de/media/wysiwyg/jajuma-develop/power-toys-magento/screenshots/Magento-Power-Toys-Core-Blank-Light.png"></td>
    </tr>
    </table>
</td>

## Requirements

* Magento v2.4.5+ OR   
  Mage-OS v1.0.0+
* Magewire v1.10+
* Magewire-requirejs v1.1+

## Further Info, Extension Description & Manual

* [Extension Website EN](https://www.jajuma.de/en/jajuma-develop/extensions/power-toys-for-magento-2)
* [Extension Website DE](https://www.jajuma.de/de/jajuma-develop/extensions/power-toys-fuer-magento-2)

## Demos

* [Magento Power Toys Demo on Luma Theme](https://www.jajuma.de/en/jajuma-shop/demo-shop-with-magento-2)
* [Magento Power Toys Demo on Hyvä Theme](https://www.jajuma.de/en/jajuma-shop/demo-shop-with-magento-2-and-hyva-themes)
* [Magento Power Toys Demo on Mage-OS](https://www.jajuma.de/en/jajuma-shop/demo-shop-with-mage-os-and-hyva-themes)

## Installation

Install via composer as any other Magento extension from github:
```
composer require jajuma/power-toys
```

## Using Power Toys For Magento

After installing this module as well as the toys you want to use:  
Go to   
**JaJuMa -> Power Toys -> Configuration**  
and enable & configure the extension.

After enabling, see the floating button at left/right edge of your screen
in your Backend and Frontend (while logged in as Admin).  
When click on this button, the Power Toys Panel will open
displaying the installed and enabled Toys.

* [See Manual for more details](https://www.jajuma.de/media/wysiwyg/jajuma-develop/power-toys-magento/manuals/JaJuMa_Power_Toys_Manual_v001.pdf)


## Available Power Toys for Magento

| Quick Action Toys                                                                                 | Dashboard Toys                                                                                 |
|---------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------|
| :broom: [Clear Cache](https://github.com/JaJuMa-GmbH/pot-clear-cache)                             | :vertical_traffic_light: [Core Web Vitals](https://github.com/JaJuMa-GmbH/pot-core-web-vitals) |
| :speech_balloon: [Chat GPT](https://github.com/JaJuMa-GmbH/pot-chat-gpt)                          | :bar_chart: [Order Status](https://github.com/JaJuMa-GmbH/pot-order-status)                    |
| :1234: [Quick Reindex](https://github.com/JaJuMa-GmbH/pot-quick-reindex)                          | :sun_behind_small_cloud: [Weather](https://github.com/JaJuMa-GmbH/pot-weather)                 |
| :arrows_counterclockwise: [Google Translate](https://github.com/JaJuMa-GmbH/pot-google-translate) | :chart_with_upwards_trend: [Matomo Reports](https://github.com/JaJuMa-GmbH/pot-matomo-reports) |
| :white_check_mark: [Todo](https://github.com/JaJuMa-GmbH/pot-todo)                                | :rocket: [Hyvä Inline CSS](https://github.com/JaJuMa-GmbH/pot-hyva-inline-css) |
| :arrows_clockwise: [Quick Translation](https://github.com/JaJuMa-GmbH/pot-quick-translation)      | :people_holding_hands: [Customers](https://github.com/JaJuMa-GmbH/pot-customers)               |
| :currency_exchange: [Currency Converter](https://github.com/JaJuMa-GmbH/pot-currency-converter)   | :newspaper: [News](https://github.com/JaJuMa-GmbH/pot-news)                                    |
| :memo: [Note](https://github.com/JaJuMa-GmbH/pot-note)                                            |                                                                                                |

## Can I Create My Own Toy?

Yes, of course!!!  
JaJuMa Power Toys for Magento 2 was created with developers in mind, allowing to integrate your own toys easily.

## Why should I Create My Own Toy?

The world needs more toys!  
Give it a go and create your own toy for you and the community...  
Creating a toy is easy and fun!

## How Can I Create My Own Toy?

Easiest way to get started is to check existing toys.  
We provide a bunch of free toys, Quick Action toys as well as Dashboard toys, covering a range of different use cases.  
Simply check a toy implementing a similar use-case as yours and use it as a template to create your own toy.


## License

The code is licensed under the [MIT License (MIT)](https://github.com/JaJuMa/power-toys/blob/master/LICENSE)

## :heart: Powered by

Developing the Power Toys module and the toys was a lot easier and more fun thanks to [Magewire](https://github.com/magewirephp/magewire).  
A big shout and Thank You to [Willem Poortman](https://github.com/wpoortman) for creating Magewire  

## Other [Magento 2 Extensions](ttps://www.jajuma.de/en/jajuma-develop/magento-extensions) by [JaJuMa](https://www.jajuma.de/)

* :framed_picture: Performance & UX:<br>[Ultimate Image Optimizer for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/ultimate-image-optimizer-extension-for-magento-2)<br>
  AVIF & WebP Images, Lazy Loading, High-Resolution / Retina images

* :framed_picture: Performance & UX:<br>[WebP Optimized Images for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/webp-optimized-images-extension-for-magento-2#portfolio-content)<br>
  The #1 WebP Images Solution for Magento 2

* :see_no_evil: SEO:<br>[PRG Pattern Link Masking for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/prg-pattern-link-masking-for-magento-2)<br>
  Link Masking for Layered Navigation

* :cop: User Experience:<br>[Shariff Social Share for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/shariff-social-share-buttons-extension-for-magento-2)<br>
  GDPR compliant and customizable Sharing Buttons

* :movie_camera: Content Management:<br>[Video Widget for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/video-widget-gdpr-extension-for-magento-2)<br>
  Embedding YouTube videos, GDPR compliant with auto preview image & fully responsive

* :rocket: Performance & UX:<br>[Page Preload for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/page-preload-extension-for-magento-2)<br>
  Faster faster page transitions and subsequent page-loads by preloading / prefetching

* :chart_with_upwards_trend: Marketing:<br>[Matomo Analytics for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/honey-spam-anti-spam-extension-for-magento-2)<br>
  Web Analytics - GDPR Compliant

* :honey_pot: Site Optimization:<br>[Honey Spam Anti-Spam for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/honey-spam-anti-spam-extension-for-magento-2)<br>
  Spam Protection - Reliable & GDPR Compliant

* :bell: Marketing:<br>[Customer Registration Reminder & Cleanup for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/customer-registration-reminder-and-cleanup-extension-for-magento-2)<br>
  Increase Your Customer Engangement & Cleanup your Customer Account Data Automatically

* :mega: UX & Marketing:<br>[Category Grid Callouts for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/category-grid-callouts-extension-for-magento-2)<br>
  Enrich Your Category Grids With Eye-Catching Callouts

* :thought_balloon: UX & Marketing:<br>[Customer Satisfaction Feedback for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/category-grid-callouts-extension-for-magento-2)<br>
  Collect Valuable Feedback From Your Customers & Understand How To Satisfy Your Customers

* :sparkler: UX:<br>[Auto Select Options for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/auto-select-options-extension-for-magento-2)<br>
  Automatically Select Configurable & Custom Options Based On Your Customer's Preferences

* :left_right_arrow: UX & Performance:<br>[Back Forward Cache - bfcache for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/back-forward-cache-extension-for-magento-2)<br>
  Enable bfcache for Magento 2 for improved UX & Core Web Vitals

* :heavy_division_sign: Accounting:<br>[Dynamic Shipping Tax Plus for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/proportional-pro-rata-dynamic-shipping-tax-plus-extension-for-magento-2)<br>
  Dynamic Shipping Tax Calculation incl. pro-rata/proportional tax rates

* :mag: Search:<br>[MySQL Search for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/magento-without-elasticsearch-mysql-search-extension-for-magento-2)<br>
  MySQL Search for Magento 2 without Elasticsearch

* :bangbang: Performance:<br>[Preload Critical Resources & Assets](https://www.jajuma.de/en/jajuma-develop/extensions/resource-hints-preload-critical-resources-assets-extension-for-magento-2)<br>
  Resource Hints for preloading important and critical resources

* :octocat: Content Management:<br>[git 4 Page Builder](https://www.jajuma.de/en/jajuma-develop/extensions/git-4-page-builder-extension-for-magento-2)<br>
  Manage & deploy Magento 2 Page Builder content via git

* :rocket: Performance:<br>[Hyvä Inline CSS](https://www.jajuma.de/en/jajuma-develop/extensions/hyva-inline-css-extension-for-magento-with-hyva-themes)<br>
  Run Magento 2 without CSS file by inline all CSS

* :man_technologist: :free: Content Management:<br>[Syntax Highlighter 4 Page Builder](https://www.jajuma.de/en/jajuma-develop/extensions/syntax-highlighter-4-page-builder-extension-for-magento-2)<br>
  Syntax Highlighting and more for Magento 2 Page Builder

* :triangular_flag_on_post: :free: UI & UX:<br>[Awesome Hyvä for Hyvä Themes](https://www.jajuma.de/en/jajuma-develop/extensions/font-awesome-icons-for-hyva-themes-extension)<br>
  Font Awesome 5 & 6 Icons for your [Hyvä Themes](https://www.jajuma.de/de/jajuma-shop/online-shop-mit-magento-2-und-hyva-themes) Store

* :triangular_flag_on_post: :free: UI & UX:<br>[Hyvä Flags for Hyvä Themes](https://www.jajuma.de/en/jajuma-develop/extensions/country-language-flag-icons-for-hyva-themes-extension)<br>
  Country & Language Flag Icons for your [Hyvä Themes](https://www.jajuma.de/de/jajuma-shop/online-shop-mit-magento-2-und-hyva-themes) Store

* :ok_man: :free: User Experience:<br>[Customer Navigation Manager for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/customer-navigation-manager-extension-for-magento-2)<br>
  Easily manage the links in your Customer Account

* :heavy_division_sign: :free: Accounting:<br>[Dynamic Shipping Tax for Magento 2](https://www.jajuma.de/en/jajuma-develop/extensions/dynamic-shipping-tax-extension-for-magento-2)<br>
  Dynamic Shipping Tax Calculation

* :question: :free: Content:<br>[Hyvä FAQ Widget for Hyvä Themes](https://www.jajuma.de/en/jajuma-develop/extensions/dynamic-shipping-tax-extension-for-magento-2)<br>
  FAQ Widget for your [Hyvä Themes](https://www.jajuma.de/de/jajuma-shop/online-shop-mit-magento-2-und-hyva-themes) Store

## Other [Services](https://www.jajuma.de/en/jajuma/company-magento-ecommerce-agency-stuttgart) by [JaJuMa](https://www.jajuma.de/)

* :shopping: [JaJuMa-Market: Marketplace Software](https://www.jajuma.de/en/jajuma-market)<br>
  Complete Online Marketplace Software Solution. For Professional Demands. Feature Rich. Flexibly Customizable.

* :shopping_cart: [JaJuMa-Shop](https://www.jajuma.de/en/jajuma-shop)<br>
  Customized Magento Shop Solutions.

* :rocket: [JaJuMa-Shop: Hyvä Magento Shop Development](https://www.jajuma.de/de/jajuma-shop/online-shop-mit-magento-2-und-hyva-themes)<br>
  Hyvä Magento Shop Development.

* :orange_book: [JaJuMa-Shop: Magento Handbuch in Deutsch](https://www.jajuma.de/de/jajuma-shop/magento-2-handbuch/)<br>
  Magento Handbuch in Deutsch.

* :card_index_dividers: [JaJuMa-PIM](https://www.jajuma.de/en/jajuma-pim)<br>
  Product Information Management. Simple. Better.

* :heavy_plus_sign: [JaJuMa-Develop: Magento 2 Extensions](https://www.jajuma.de/en/jajuma-develop/magento-extensions)<br>
  Individual Solutions For Your Business Case.

* :paintbrush: [JaJuMa-Design](https://www.jajuma.de/en/jajuma-design)<br>
  Designs That Inspire.

* :necktie: [JaJuMa-Consult](https://www.jajuma.de/en/jajuma-consult)<br>
  We Show You New Perspectives.

© JaJuMa GmbH | [www.jajuma.de](www.jajuma.de)