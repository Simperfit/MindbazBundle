<?php

/*
 * This file is part of the MindbazBundle package.
 *
 * (c) David DELEVOYE <david.delevoye@adeo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Kozikaza\MindbazBundle\Serializer;

use Kozikaza\MindbazBundle\Model\Subscriber;
use Kozikaza\MindbazBundle\Serializer\SubscriberNormalizer;
use PhpSpec\ObjectBehavior;

/**
 * @author Vincent Chalamon <vincent@les-tilleuls.coop>
 */
class SubscriberNormalizerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SubscriberNormalizer::class);
    }

    function it_supports_normalization(Subscriber $subscriber)
    {
        $this->supportsNormalization($subscriber)->shouldBeTrue();
    }

    function it_does_not_support_normalization(\stdClass $object)
    {
        $this->supportsNormalization($object)->shouldBeFalse();
    }

    function it_supports_denormalization()
    {
        $this->supportsDenormalization([], Subscriber::class)->shouldBeTrue();
    }

    function it_does_not_support_denormalization()
    {
        $this->supportsDenormalization([], \stdClass::class)->shouldBeFalse();
    }

    function it_normalizes()
    {
        $subscriber = new Subscriber();
        $subscriber->setEmail('foo@example.com');
        $subscriber->setFirstName('John');
        $subscriber->setLastName('DOE');

        $this->normalize($subscriber)->shouldBeEqualTo([
            'email'     => 'foo@example.com',
            'lastName'  => 'DOE',
            'firstName' => 'John',
        ]);
    }

    function it_denormalizes()
    {
        $subscriber = new Subscriber();
        $subscriber->setEmail('foo@example.com');
        $subscriber->setFirstName('John');
        $subscriber->setLastName('DOE');

        $result = $this->denormalize([
            'email'     => 'foo@example.com',
            'lastName'  => 'DOE',
            'firstName' => 'John',
        ], Subscriber::class);
        $result->shouldBeInstanceOf(Subscriber::class);
        $result->shouldHavePropertyEqualTo('email', 'foo@example.com');
        $result->shouldHavePropertyEqualTo('firstName', 'John');
        $result->shouldHavePropertyEqualTo('lastName', 'DOE');
    }

    function getMatchers()
    {
        return [
            'beTrue' => function ($subject) {
                return true === $subject;
            },
            'beFalse' => function ($subject) {
                return false === $subject;
            },
            'beInstanceOf' => function ($subject, $class) {
                return $subject instanceof $class;
            },
            'havePropertyEqualTo' => function ($subject, $property, $value) {
                $reflectionProperty = new \ReflectionProperty(get_class($subject), $property);
                $reflectionProperty->setAccessible(true);

                return $value === $reflectionProperty->getValue($subject);
            },
        ];
    }
}
