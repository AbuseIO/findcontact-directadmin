<?php

namespace AbuseIO\FindContact;

use AbuseIO\Models\Account;
use AbuseIO\Models\Contact;
use RipeStat\AbuseContactFinder;
use DirectAdmin\DirectAdmin as Finder;

use Log;

/**
 * Class Directadmin
 * @package AbuseIO\FindContact
 */
class Directadmin
{
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
        $this->finder->connect(
            $this->getDomain(),
            $this->getPort()
        );
    }

    /**
     * Get the abuse email address registered for this ip.
     * @param  string $ip IPv4 Address
     * @return mixed Returns contact object or false.
     */
    public function getContactByIp($ip)
    {
        $result = false;

        try {
            $data = $this->_getContactDataForIp($ip);

            if (!empty($data)) {
                $result = $this->getContactWithData($data);
            }

        } catch (\Exception $e) {
            Log::debug("Error while talking to the Directadmin Stat API : " . $e->getMessage());
        }
        return $result;
    }


    /**
     * Get the email address registered for this domain.
     * @param  string $domain Domain name
     * @return mixed Returns contact object or false.
     */
    public function getContactByDomain($domain)
    {
        $result = false;

        try {
            $data = $this->_getContactDataForDomain($domain);

            if (!empty($data)) {
                $result = $this->getContactWithData($data);
            }

        } catch (\Exception $e) {
            Log::debug("Error while talking to the Directadmin Stat API : " . $e->getMessage());
        }
        return $result;
    }

    /**
     * Get the email address registered for this ip.
     * @param  string $id ID/Contact reference
     * @return mixed Returns contact object or false.
     */
    public function getContactById($id)
    {
        return false;
    }

    /**
     * search the ip using the directadmin api and if found, return the data for the user
     *
     * @param $ip
     * @return array
     */
    private function _getContactDataForIp($ip)
    {
        return $this->_getContactDataForQuery(
            sprintf(
                'CMD_ALL_USER_SHOW?value7=%s&comparison8=contains&sort1=8&sort1dir=1&sort2dir=1&ipp=50&json=yes',
                $ip
            )
        );
    }

    /**
     * search the domain using the directadmin api and if found, return the data for the user
     *
     * @param $domain
     * @return array
     */
    private function _getContactDataForDomain($domain)
    {
        return $this->_getContactDataForQuery(
            sprintf(
                'CMD_ALL_USER_SHOW?value8=%s&comparison8=contains&sort1=8&sort1dir=1&sort2dir=1&ipp=50&json=yes',
                $domain
            )
        );
    }

    /**
     * return the data for the user based on a provided query
     *
     * @param $query
     * @return array
     */
    private function _getContactDataForQuery($query)
    {
        $data = [];
        $name = null;
        $email = null;


        $this->finder->query($query);

        $data = $this->finder->fetch_body();

        // only create a result data if both email and name are set
        if (!is_null($name) && !is_null($email)) {
            $data['name'] = $name;
            $data['email'] = $email;
        }

        return $data;
    }

    /**
     * return the config info for the port default = 2222
     *
     * @return int
     */
    private function getPort()
    {
        return config('Findcontact.findcontact-directadmin.port') ? config('Findcontact.findcontact-directadmin.port') : 2222;
    }

    /**
     * return the config info for the domain where the directadmin instance is located;
     *
     * @return mixed
     * @throws \Exception
     */
    private function getDomain()
    {
        $value = config('Findcontact.findcontact-directadmin.domain');
        if (!$value) {
            throw new \Exception('please set findcontact-directadmin.domain in config/Directadmin.php');
        }

        return $value;
    }

    /**
     * @param $data
     * @return Contact
     */
    private function getContactWithData($data)
    {
        // construct new contact
        $result = new Contact();
        $result->name = $data['name'];
        $result->reference = $data['name'];
        $result->email = $data['email'];
        $result->enabled = true;
        $result->auto_notify = config("Findcontact.findcontact-directadmin.auto_notify");
        $result->account_id = Account::getSystemAccount()->id;
        $result->api_host = '';
        return $result;
    }
}
