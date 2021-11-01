<?php


namespace Tests\Model\Thermostat\Cron;

use App\Backend\Modules\Thermostat\Helper\Power;
use DateInterval;
use DateTime;
use Entity\Thermostat;
use Model\ThermostatManagerPDO;
use OCFram\Managers;
use OCFram\PDOFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class PowerTest
 * @package Tests\Model\Thermostat\Cron
 */
class PowerTest extends TestCase
{
    /**
     * @var \PDO
     */
    public static $db;

    /**
     * @var Managers $managers
     */
    public static $managers;

    /**
     *
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        PDOFactory::setPdoAddress(TEST_DB_PATH);
        self::$db = PDOFactory::getSqliteConnexion();
        self::$managers = new Managers('PDO', self::$db);
    }

    /**
     * @throws \Exception
     */
    public function testCanSendPwrOnEmail()
    {
        $power = new Power();
        /** @var ThermostatManagerPDO $thermostatManager */
        $thermostatManager = self::$managers->getManagerOf('Thermostat');
        /** @var Thermostat $thermostat */
        $thermostat = current($thermostatManager->getList());

        // power off 6 minutes ago with a 5 minutes delay, result must be true
        $datetime = new DateTime();
        $datetime->sub(new DateInterval('PT6M'));
        $thermostat->setLastPwrOff($datetime->format('20y-m-d H:i:s'));
        $result = $power->canSendPwrOnEmail($thermostat, 5);
        $this->assertTrue($result);

        // power off 30 minutes ago with a 35 minutes delay, result must be false
        $datetime = new DateTime();
        $datetime->sub(new DateInterval('PT30M'));
        $thermostat->setLastPwrOff($datetime->format('20y-m-d H:i:s'));
        $result = $power->canSendPwrOnEmail($thermostat, 35);
        $this->assertFalse($result);

        // power off 30 minutes ago with a 29 minutes delay, result must be true
        $datetime = new DateTime();
        $datetime->sub(new DateInterval('PT30M'));
        $thermostat->setLastPwrOff($datetime->format('20y-m-d H:i:s'));
        $result = $power->canSendPwrOnEmail($thermostat, 29);
        $this->assertTrue($result);
    }
}