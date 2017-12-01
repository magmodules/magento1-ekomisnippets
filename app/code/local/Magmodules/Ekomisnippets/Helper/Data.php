<?php
/**
 * Magmodules.eu - http://www.magmodules.eu
 *
 * NOTICE OF LICENSE
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magmodules.eu so we can send you a copy immediately.
 *
 * @category      Magmodules
 * @package       Magmodules_Ekomisnippets
 * @author        Magmodules <info@magmodules.eu>
 * @copyright     Copyright (c) 2017 (http://www.magmodules.eu)
 * @license       http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Magmodules_Ekomisnippets_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * @return bool
     */
    function getSnapshopRequest()
    {
        $ekomiApiId = Mage::getStoreConfig('ekomisnippets/api/api_id');
        $ekomiApiKey = Mage::getStoreConfig('ekomisnippets/api/api_key');
        $ekomiVersion = 'cust-1.0.0';

        if ($ekomiApiId && $ekomiApiKey) {
            try {
                $api = 'http://api.ekomi.de/v2/wsdl';
                $client = new SoapClient($api, array('exceptions' => 0));
                $sendSnapshotRequest = $client->getSnapshot($ekomiApiId . '|' . $ekomiApiKey, $ekomiVersion);
                $ret = @unserialize(utf8_decode($sendSnapshotRequest));
                if ($ret['done']) {
                    $snippets = $ret['info'];
                    if ($snippets['fb_count'] > 0) {
                        return $snippets;
                    }
                }
            } catch (SoapFault $e){
                Mage::logException($e);
            }
        }
        return false;
    }

    /**
     * @return bool|string
     */
    function getEkomiLink()
    {
        if (Mage::getStoreConfig('ekomisnippets/api/show_link')) {
            $ekomiLink = Mage::getStoreConfig('ekomisnippets/api/ekomi_link');
            return Mage::helper('ekomisnippets')->__('customer reviews on') . ' <a href="' . $ekomiLink . '" target="_blank">Ekomi</a>';
        } else {
            return false;
        }
    }

    /**
     * @param $rating
     *
     * @return string
     */
    function getEkomiStars($rating)
    {
        $perc = round(($rating * 20), 0);
        $html = '<div class="rating-box">';
        $html .= '	<div class="rating" style="width:' . $perc . '%"></div>';
        $html .= '</div>';
        return $html;
    }

}