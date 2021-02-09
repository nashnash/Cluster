<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $manager->persist($this->getAdmin());
        $manager->persist($this->getProfessionalUser());
        $manager->persist($this->getUser());
        $manager->persist($this->getPremiumUser());
        $manager->persist($this->getInativeUser());

        $manager->flush();
    }

    /**
     * @return User
     */
    private function getAdmin()
    {

        $admin = new User();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setLastname('ADMIN');
        $admin->setFirstname('ADMIN');
        $admin->setEmail('admin@admin.com');
        $hash = $this->encoder->encodePassword($admin, 'admin');
        $admin->setPassword($hash);
        $admin->setAddress('1 Rue de Paris');
        $admin->setCity('Paris');
        $admin->setExp(rand(0, 400));
        $admin->setCreatedAt(new DateTime('now'));
        $admin->setUpdatedAt(new DateTime('now'));
        $admin->setLastActivity(new DateTime('now'));
        $admin->setPremium(false);
        $admin->setActive(true);
        $admin->setRating(5);

        return $admin;
    }

    /**
     * @return User
     */
    private function getProfessionalUser()
    {

        return (new User())->setRoles(['ROLE_PRO'])
            ->setLastname('PRO')
            ->setFirstname('PRO')
            ->setEmail('pro@pro.com')
            ->setPassword($this->encoder->encodePassword(new User(), 'admin'))
            ->setAddress('1 Rue de Paris')
            ->setCity('Paris')
            ->setExp(rand(0, 400))
            ->setCreatedAt(new DateTime('now'))
            ->setUpdatedAt(new DateTime('now'))
            ->setLastActivity(new DateTime('now'))
            ->setPremium(false)
            ->setActive(true)
            ->setRating(5);
    }

    /**
     * @return User
     */
    private function getUser()
    {

        return (new User())->setRoles(['ROLE_USER'])
            ->setLastname('USER')
            ->setFirstname('USER')
            ->setEmail('user@user.com')
            ->setPassword($this->encoder->encodePassword(new User(), 'user'))
            ->setAddress('1 Rue de Paris')
            ->setCity('Paris')
            ->setExp(rand(0, 400))
            ->setCreatedAt(new DateTime('now'))
            ->setUpdatedAt(new DateTime('now'))
            ->setLastActivity(new DateTime('now'))
            ->setPremium(false)
            ->setActive(true)
            ->setRating(5);
    }

    /**
     * @return User
     */
    private function getPremiumUser()
    {

        return (new User())->setRoles(['ROLE_USER'])
            ->setLastname('PREMIUM')
            ->setFirstname('PREMIUM')
            ->setEmail('premium@premium.com')
            ->setPassword($this->encoder->encodePassword(new User(), 'premium'))
            ->setAddress('1 Rue de Paris')
            ->setCity('Paris')
            ->setExp(rand(0, 400))
            ->setCreatedAt(new DateTime('now'))
            ->setUpdatedAt(new DateTime('now'))
            ->setLastActivity(new DateTime('now'))
            ->setPremium(true)
            ->setRating(5)
            ->setActive(true)
            ->setPremiumEndDate((new DateTime(''))->modify('+ 1 month'));
    }

    /**
     * @return User
     */
    private function getInativeUser()
    {

        return (new User())->setRoles(['ROLE_USER'])
            ->setLastname('INACTIVE')
            ->setFirstname('INACTIVE')
            ->setEmail('inactive@inactive.com')
            ->setPassword($this->encoder->encodePassword(new User(), 'inactive'))
            ->setAddress('1 Rue de Paris')
            ->setCity('Paris')
            ->setExp(rand(0, 400))
            ->setCreatedAt(new DateTime('now'))
            ->setUpdatedAt(new DateTime('now'))
            ->setLastActivity(new DateTime('now'))
            ->setActive(false)
            ->setRating(5)
            ->setPremiumEndDate((new DateTime(''))->modify('+ 1 month'));
    }
}
