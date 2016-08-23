<?php

namespace Jarenal\TinyTasksBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Jarenal\TinyTasksBundle\Entity\State;

class LoadStateData implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$list = array('Pending', 'In progress', 'Finished');


		foreach($list as $status)
		{
			$new = new State();
			$new->setName($status);
			$manager->persist($new);
			$manager->flush();
			unset($new);
		}

	}
}