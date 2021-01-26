<?php


namespace App\Tests\Entity;


use App\Entity\Chat;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class ChatTest extends KernelTestCase
{

    public function getEntity(): Chat
    {
        return (new Chat())
            ->setName('Chat')
            ->setCreatedAt(new DateTime());
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testValidEntityWithoutSetCreatedAt()
    {
        $entity = (new Chat())->setName('Chat');
        $this->assertHasErrors($entity, 0);
    }

    public function testValidEntityWithEmptyName()
    {
        $entity = new Chat();
        $this->assertHasErrors($entity, 0);
    }

    public function assertHasErrors(Chat $chat, int $numbers = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($chat);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($numbers, $errors, implode(', ', $messages));
    }
}