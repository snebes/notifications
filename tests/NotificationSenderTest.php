<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\Tools\SchemaTool;
use SN\Notifications\NotificationSender;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Fixtures\Entity\User;

class NotificationSenderTest extends KernelTestCase
{
    /**
     * @var NotificationSender
     */
    private $sender;

    protected function setUp(): void
    {
        $kernel = static::bootKernel();
        $this->sender = $kernel->getContainer()->get(NotificationSender::class);

        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $meta = $em->getMetadataFactory()->getAllMetadata();

        if (!empty($meta)) {
            try {
                $tool = new SchemaTool($em);
                $tool->dropSchema($meta);
                $tool->createSchema($meta);
            } catch (\Exception $e) {
            }
        }
    }

    public function testEvent()
    {
        $em = static::$container->get('doctrine')->getManager();

        $user = new User();

        $this->assertSame(0, $user->getId());

        $em->persist($user);
        $em->flush();

        $this->assertGreaterThan(0, $user->getId());
    }
}
