<?php

/*
 * This file is part of the Gravatar XML-RPC API package.
 *
 * (c) Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gravatar\Xmlrpc\Exception;

/**
 * Most common faults are covered by the doc
 *
 * @link http://en.gravatar.com/site/implement/xmlrpc/
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Fault extends \Exception
{
    /**
     * Fault responses
     */
    const INVALID_URL          = -7;
    const INTERNAL_ERROR       = -8;
    const AUTHENTICATION_ERROR = -9;
    const MISSING_PARAMETER    = -10;
    const INCORRECT_PARAMETER  = -11;

    /**
     * @var array
     */
    private static $exceptionMap = [
        -7  => 'Gravatar\Xmlrpc\Exception\Fault\InvalidUrl',
        -8  => 'Gravatar\Xmlrpc\Exception\Fault\InternalError',
        -9  => 'Gravatar\Xmlrpc\Exception\Fault\AuthenticationError',
        -10 => 'Gravatar\Xmlrpc\Exception\Fault\MissingParameter',
        -11 => 'Gravatar\Xmlrpc\Exception\Fault\IncorrectParameter',
    ];

    /**
     * Creates a new Fault
     *
     * If there is a mach for the fault code in the exception map then the matched exception will be returned
     *
     * @param string  $faultString
     * @param integer $faultCode
     *
     * @return self
     */
    public static function create($faultString, $faultCode)
    {
        if (!isset(self::$exceptionMap[$faultCode])) {
            return new self($faultString, $faultCode);
        }

        return new self::$exceptionMap[$faultCode]($faultString, $faultCode);
    }
}
