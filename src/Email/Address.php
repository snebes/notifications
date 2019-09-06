<?php
/**
 * (c) Steve Nebes (steve@nebes.net)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\Notifications\Email;

/**
 * @author Steve Nebes <steve@nebes.net>
 */
final class Address
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $name;

    /**
     * Email address and name.
     *
     * @param string $address
     * @param string $name
     */
    public function __construct(string $address, string $name = '')
    {
        $this->address = \trim($address);
        $this->name = \trim(\str_replace(["\n", "\r"], '', $name));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return ($name = $this->getName()) ? $name . ' <'.$this->getAddress() . '>' : $this->getAddress();
    }

    /**
     * @param Address|string $address
     *
     * @return Address
     */
    public static function create($address): self
    {
        if ($address instanceof self) {
            return $address;
        } elseif (\is_string($address)) {
            return new self($address);
        }

        $type = \is_object($address) ? \get_class($address) : \gettype($address);

        throw new \InvalidArgumentException(
            \sprintf('An address can be an instance of Address or a string, ("%s") given.', $type));
    }

    /**
     * @param Address[]|string[] $addresses
     *
     * @return array
     */
    public static function createArray(array $addresses): array
    {
        $list = [];

        foreach ($addresses as $address) {
            $list[] = self::create($address);
        }

        return $list;
    }
}
