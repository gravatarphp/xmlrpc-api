<?php

/*
 * This file is part of the Gravatar XML-RPC API package.
 *
 * (c) Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gravatar\Xmlrpc;

use fXmlRpc\CallClientInterface;
use fXmlRpc\Exception\ResponseException;

/**
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @link http://en.gravatar.com/site/implement/xmlrpc/
 */
class Client
{
    /**
     * @var CallClientInterface
     */
    protected $xmlrpcClient;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param CallClientInterface $xmlrpcClient
     * @param string              $password
     */
    public function __construct(CallClientInterface $xmlrpcClient, $password)
    {
        $this->xmlrpcClient = $xmlrpcClient;
        $this->password = $password;
    }

    /**
     * Checks whether a hash has a gravatar
     *
     * @param array $hashes
     *
     * @return array
     */
    public function exists(array $hashes)
    {
        $params = compact('hashes');

        return $this->call(__FUNCTION__, $params);
    }

    /**
     * Returns a list of addresses for this account
     *
     * @return array
     */
    public function addresses()
    {
        return $this->call(__FUNCTION__);
    }

    /**
     * Returns an array of userimages for this account
     *
     * @return array
     */
    public function userimages()
    {
        return $this->call(__FUNCTION__);
    }

    /**
     * Save binary image data as a userimage for this account
     *
     * @param string  $data
     * @param integer $rating
     *
     * @return boolean|string
     */
    public function saveData($data, $rating)
    {
        $params = compact('data', 'rating');

        return $this->call(__FUNCTION__, $params);
    }

    /**
     * Reads an image via its URL and save that as a userimage for this account
     *
     * @param string  $url
     * @param integer $rating
     *
     * @return boolean|string
     */
    public function saveUrl($url, $rating)
    {
        $params = compact('url', 'rating');

        return $this->call(__FUNCTION__, $params);
    }

    /**
     * Use a userimage as a gravatar for one of more addresses on this account
     *
     * @param string $userimage
     * @param array  $addresses
     *
     * @return array
     */
    public function useUserimage($userimage, array $addresses)
    {
        $params = compact('userimage', 'addresses');

        return $this->call(__FUNCTION__, $params);
    }

    /**
     * Removes the userimage associated with one or more email addresses
     *
     * @param array $addresses
     *
     * @return array
     */
    public function removeImage(array $addresses)
    {
        $params = compact('addresses');

        return $this->call(__FUNCTION__, $params);
    }

    /**
     * Removes a userimage from the account and any email addresses with which it is associated
     *
     * @param string $userimage
     *
     * @return boolean
     */
    public function deleteUserimage($userimage)
    {
        $params = compact('userimage');

        return $this->call(__FUNCTION__, $params);
    }

    /**
     * Test function
     *
     * @param array $params
     *
     * @return array
     */
    public function test(array $params)
    {
        return $this->call(__FUNCTION__, $params);
    }

    /**
     * Invokes the XML-RPC Client
     *
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     */
    protected function call($method, array $params = [])
    {
        $params['password'] = $this->password;

        try {
            return $this->xmlrpcClient->call('grav.'.$method, $params);
        } catch (ResponseException $e) {
            throw Exception\Fault::create($e->getFaultString(), $e->getFaultCode());
        }
    }
}
