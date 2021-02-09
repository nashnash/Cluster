<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class BadgeFixtures
 * @package App\DataFixtures
 */
class BadgeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->getFirstConversationBadge());
        $manager->persist($this->getFriendsBadge());
        $manager->persist($this->getErmiteBadge());
        $manager->flush();
    }

    /**
     * @return Badge
     */
    private function getFirstConversationBadge(): Badge
    {
        return (new Badge())
            ->setName('Sort de sa tanière')
            ->setSlug('sort-de-ta-taniere')
            ->setDescription('Créer une conversation de groupe avec au moins 3 personnes');
    }

    /**
     * @return Badge
     */
    private function getFriendsBadge(): Badge
    {
        return (new Badge())
            ->setName('Sociable')
            ->setSlug('sociable')
            ->setDescription('Ajoute ton premier ami');
    }

    /**
     * @return Badge
     */
    private function getErmiteBadge(): Badge
    {
        return (new Badge())
            ->setName('Ermite Out')
            ->setSlug('ermite-out')
            ->setDescription('Ermite sort de ce corps (Participe à ton premier évènement)');
    }
}
