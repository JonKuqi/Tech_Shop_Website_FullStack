<?php

/**
 * PHPMailer POP-Before-SMTP Authentication Class.
 * PHP Version 5.5.
 *
 * @see https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 *
 * @author    Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author    Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author    Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 * @author    Brent R. Matzelle (original founder)
 * @copyright 2012 - 2020 Marcus Bointon
 * @copyright 2010 - 2012 Jim Jagielski
 * @copyright 2004 - 2009 Andy Prevost
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace PHPMailer\PHPMailer;

/**
 * PHPMailer POP-Before-SMTP Authentication Class.
 * Specifically for PHPMailer to use for RFC1939 POP-before-SMTP authentication.
 * 1) This class does not support APOP authentication.
 * 2) Opening and closing lots of POP3 connections can be quite slow. If you need
 *   to send a batch of emails then just perform the authentication once at the start,
 *   and then loop through your mail sending script. Providing this process doesn't
 *   take longer than the verification period lasts on your POP3 server, you should be fine.
 * 3) This is really ancient technology; you should only need to use it to talk to very old systems.
 * 4) This POP3 class is deliberately lightweight and incomplete, implementing just
 *   enough to do authentication.
 *   If you want a more complete class there are other POP3 classes for PHP available.
 *
 * @author Richard Davey (original author) <rich@corephp.co.uk>
 * @author Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 */

class POP3
{
    /**
     * The POP3 PHPMailer Version number.
     *
     * @var string
     */
    const VERSION = '6.9.1';

    /**
     * Default POP3 port number.
     *
     * @var int
     */
    const DEFAULT_PORT = 110;

    /**
     * Default timeout in seconds.
     *
     * @var int
     */
    const DEFAULT_TIMEOUT = 30;

    /**
     * POP3 class debug output mode.
     * Debug output level.
     * Options:
     * @see POP3::DEBUG_OFF: No output
     * @see POP3::DEBUG_SERVER: Server messages, connection/server errors
     * @see POP3::DEBUG_CLIENT: Client and Server messages, connection/server errors
     *
     * @var int
     */
    public $do_debug = self::DEBUG_OFF;

    /**
     * POP3 mail server hostname.
     *
     * @var string
     */
    public $host;

    
    /**
     * POP3 port number.
     *
     * @var int
     */
    public $port;

    /**
     * POP3 Timeout Value in seconds.
     *
     * @var int
     */
    public $tval;

    /**
     * POP3 username.
     *
     * @var string
     */
    public $username;

    /**
     * POP3 password.
     *
     * @var string
     */
    public $password;

    /**
     * Resource handle for the POP3 connection socket.
     *
     * @var resource
     */
    protected $pop_conn;

        /**
     * Are we connected?
     *
     * @var bool
     */
    protected $connected = false;

    /**
     * Error container.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Line break constant.
     */
    const LE = "\r\n";

    /**
     * Debug level for no output.
     *
     * @var int
     */
    const DEBUG_OFF = 0;

    /**
     * Debug level to show server -> client messages
     * also shows clients connection errors or errors from server
     *
     * @var int
     */
    const DEBUG_SERVER = 1;
        /**
     * Debug level to show client -> server and server -> client messages.
     *
     * @var int
     */
    const DEBUG_CLIENT = 2;

    /**
     * Simple static wrapper for all-in-one POP before SMTP.
     *
     * @param string   $host        The hostname to connect to
     * @param int|bool $port        The port number to connect to
     * @param int|bool $timeout     The timeout value
     * @param string   $username
     * @param string   $password
     * @param int      $debug_level
     *
     * @return bool
     */
    public static function popBeforeSmtp(
        $host,
        $port = false,
        $timeout = false,
        $username = '',
        $password = '',
        $debug_level = 0
    ) {
        $pop = new self();

        return $pop->authorise($host, $port, $timeout, $username, $password, $debug_level);
    }

