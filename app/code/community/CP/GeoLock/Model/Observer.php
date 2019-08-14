<?php
 /**
 * GeoLock extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   CP
 * @package    CP_GeoLock
 * @author     Commercepundit<magento@commercepundit.com>
 */ 
include(getcwd()."/lib/GeoLock/geoip.inc");
class CP_GeoLock_Model_Observer
{
	private $country;
	private $ip;
	private $allowed_ip;
	private $allowed_countries;
	
    public function controllerActionPredispatch($observer)
    {
		
		if((string)Mage::getStoreConfig('cpgeolock/geolock/enabled') == 1)
		{
			$gi = geoip_open(getcwd()."/lib/GeoLock/GeoIP.dat", GEOIP_STANDARD);
			
			$this->allowed_ip = explode(',', (string)Mage::getStoreConfig('cpgeolock/geolock/allow_ip'));
			$this->allowed_countries = explode(',', (string)Mage::getStoreConfig('cpgeolock/geolock/allow_country'));
			
			$this->ip = $_SERVER['REMOTE_ADDR'];
			$this->country = geoip_country_code_by_addr($gi,$this->ip);
			
			geoip_close($gi);
			
			if(!$this->isIpAllowed($this->ip))
			{
				if(!$this->isCountryAllowed($this->country))
				{
					$this->_redirectPage();
				}
			}
		}
    }
	public function _redirectPage()
	{
		
		$page = (string)Mage::getStoreConfig('cpgeolock/geolock/redirect_page'); //exit;
		$desti = Mage::getUrl($page);
		if($desti != Mage::helper('core/url')->getCurrentUrl())
		{
			$response = Mage::app()->getResponse();
			$response->setRedirect($desti,301);
			$response->sendResponse();
			exit;
		}
		
	
	}
	public function isIpAllowed($ip='')
	{
		 
		$ip = $ip ? $ip :  $this->ip;
		if (count($this->allowed_ip) && $ip) {
            return in_array($ip, $this->allowed_ip);
        } else {
            return true;
        }
		
	}
	public function isCountryAllowed($country = '')
    {
        $country = $country ? $country : $this->country;
        if (count($this->allowed_countries) && $country) {
            return in_array($country, $this->allowed_countries );
        } else {
            return true;
        }
    }
}