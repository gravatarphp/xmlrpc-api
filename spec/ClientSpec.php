<?php

namespace spec\Gravatar\Xmlrpc;

use fXmlRpc\ClientInterface;
use fXmlRpc\Exception\ResponseException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function let(ClientInterface $client)
    {
        $this->beConstructedWith($client, 'secret_word');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Gravatar\Xmlrpc\Client');
    }

    function it_checks_a_hash_existence(ClientInterface $client)
    {
        $hashes = ['hash1', 'hash2'];
        $response = [
            'hash1' => true,
            'hash2' => false,
        ];

        $client->call('grav.exists', [
            'hashes'   => $hashes,
            'password' => 'secret_word',
        ])->willReturn($response);

        $this->exists($hashes)->shouldReturn($response);
    }

    function it_returns_addresses(ClientInterface $client)
    {
        $response = [
            'user@domain.com' => [
                'rating'        => 0,
                'userimage'     => 1,
                'userimage_url' => '?',
            ],
        ];

        $client->call('grav.addresses', [
            'password' => 'secret_word',
        ])->willReturn($response);

        $this->addresses()->shouldReturn($response);
    }

    function it_returns_user_images(ClientInterface $client)
    {
        $response = [
            1 => [0, '?'],
        ];

        $client->call('grav.userimages', [
            'password' => 'secret_word',
        ])->willReturn($response);

        $this->userimages()->shouldReturn($response);
    }

    function it_uploads_an_image(ClientInterface $client)
    {
        $data = base64_encode('image');
        $rating = 0;
        $response = 'userimage';

        $client->call('grav.saveData', [
            'data'     => $data,
            'rating'   => $rating,
            'password' => 'secret_word',
        ])->willReturn($response);

        $this->saveData($data, $rating)->shouldReturn($response);
    }

    function it_uploads_an_image_from_url(ClientInterface $client)
    {
        $url = 'url';
        $rating = 0;
        $response = 'userimage';

        $client->call('grav.saveUrl', [
            'url'      => $url,
            'rating'   => $rating,
            'password' => 'secret_word',
        ])->willReturn($response);

        $this->saveUrl($url, $rating)->shouldReturn($response);
    }

    function it_sets_the_used_image(ClientInterface $client)
    {
        $userimage = 'userimage';
        $addresses = ['user@domain.com'];
        $response = ['user@domain.com' => true];

        $client->call('grav.useUserimage', [
            'userimage' => $userimage,
            'addresses' => $addresses,
            'password'  => 'secret_word',
        ])->willReturn($response);

        $this->useUserimage($userimage, $addresses)->shouldReturn($response);
    }

    function it_removes_an_image(ClientInterface $client)
    {
        $addresses = ['user@domain.com'];
        $response = ['user@domain.com' => true];

        $client->call('grav.removeImage', [
            'addresses' => $addresses,
            'password'  => 'secret_word',
        ])->willReturn($response);

        $this->removeImage($addresses)->shouldReturn($response);
    }

    function it_deletes_an_image(ClientInterface $client)
    {
        $userimage = 'userimage';
        $response = true;

        $client->call('grav.deleteUserimage', [
            'userimage' => $userimage,
            'password'  => 'secret_word',
        ])->willReturn($response);

        $this->deleteUserimage($userimage)->shouldReturn($response);
    }

    function it_returns_test_data(ClientInterface $client)
    {
        $test = 'test';
        $response = ['test' => $test];

        $client->call('grav.test', [
            'test'     => $test,
            'password' => 'secret_word',
        ])->willReturn($response);

        $this->test($response)->shouldReturn($response);
    }

    function it_throws_a_fault_exception_when_something_went_bad(ClientInterface $client)
    {
        $e = ResponseException::fault(['faultString' => '', 'faultCode' => -7]);

        $client->call(Argument::type('string'), Argument::type('array'))->willThrow($e);

        $this->shouldThrow('Gravatar\Xmlrpc\Exception\Fault\InvalidUrl')->duringTest([]);
    }
}
