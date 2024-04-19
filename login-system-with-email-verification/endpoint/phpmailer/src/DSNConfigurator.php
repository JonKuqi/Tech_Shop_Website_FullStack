<?php

/**
 * PHPMailer - PHP email creation and transport class.
 * PHP Version 5.5.
 *
 * @see https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 *
 * @author    Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author    Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author    Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 * @author    Brent R. Matzelle (original founder)
 * @copyright 2012 - 2023 Marcus Bointon
 * @copyright 2010 - 2012 Jim Jagielski
 * @copyright 2004 - 2009 Andy Prevost
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace PHPMailer\PHPMailer;

/**
 * Configure PHPMailer with DSN string.
 *
 * @see https://en.wikipedia.org/wiki/Data_source_name
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class DSNConfigurator
{
    /**
     * Create new PHPMailer instance configured by DSN.
     *
     * @param string $dsn        DSN
     * @param bool   $exceptions Should we throw external exceptions?
     *
     * @return PHPMailer
     */
    public static function mailer($dsn, $exceptions = null)
    {
        static $configurator = null;

        if (null === $configurator) {
            $configurator = new DSNConfigurator();
        }

        return $configurator->configure(new PHPMailer($exceptions), $dsn);
    }

    /**
     * Configure PHPMailer instance with DSN string.
     *
     * @param PHPMailer $mailer PHPMailer instance
     * @param string    $dsn    DSN
     *
     * @return PHPMailer
     */
    public function configure(PHPMailer $mailer, $dsn)
    {
        $config = $this->parseDSN($dsn);

        $this->applyConfig($mailer, $config);

        return $mailer;
    }