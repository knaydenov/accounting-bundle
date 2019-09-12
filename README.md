# KnaAccountingBundle

This Bundle provides event-driven accounting implementation.

## Installation

```shell script
composer require kna/accounting-bundle
```

## Configuring

### Add config

```yaml
// config/packages/kna_accounting.yaml

kna_accounting:
  account_class: App/Entity/Account # default
  entry_class: App/Entity/Entry # default
  event_class: App/Entity/Event # default
  event_discriminator_map:
    payment: App/Entity/PaymentEvent
    sale: App/Entity/SaleEvent
```

### Create base entities

Account:

```php
<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kna\AccountingBundle\Entity\BaseAccount;

class Account extends BaseAccount
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
```

Entry:

```php
<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kna\AccountingBundle\Entity\BaseEntry;

class Base extends BaseEntry
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
```

Event:

```php
<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kna\AccountingBundle\Entity\BaseEvent;

abstract class Event extends BaseEvent
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
```

### Create events

For example:

```php
<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class PaymentEvent extends Event
{
    /**
     * @ORM\ManyToOne(targetEntity="Account")
     */
    protected $account;
}
```

and

```php
<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class SaleEvent extends Event
{
    /**
     * @ORM\ManyToOne(targetEntity="Order")
     */
    protected $order;
}
```

### Create event provider

```php
<?php
namespace App\Entity\Accounting;

use App\Entity\Payment;
use App\Entity\Order;
use App\Entity\PaymentEvent;
use App\Entity\SaleEvent;
use Money\Currency;
use Money\Money;
use Kna\AccountingBundle\Accounting\BaseEventProvider;

class DefaultEventProvider extends BaseEventProvider
{
    /**
     * {@inheritDoc}
     */
    public function supports($source): bool
    {
        return 
            $source instanceof Payment ||
            $source instanceof Order
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function createEvents($source): \Generator
    {
        if ($source instanceof Payment) {
            $event = new PaymentEvent();
            $event->setAmount(new Money($source->getAmount(), new Currency($source->getCurrency())));
            $event->setAccount($source->getAccount());
            $event->setOccurredAt(new \DateTime());
            $event->setNoticedAt(new \DateTime());
            yield $event;
        } elseif ($source instanceof Order) {
            $event = new SaleEvent();
            $event->setAmount(new Money($source->getAmount(), new Currency($source->getCurrency())));
            $event->setOrder($source);
            $event->setOccurredAt(new \DateTime());
            $event->setNoticedAt(new \DateTime());
            yield $event;
        }
    }
}
```

### Create entry provider

```php
<?php
namespace App\Entity\Accounting;

use App\Entity\PaymentEvent;
use App\Entity\SaleEvent;
use Kna\AccountingBundle\Model\EventInterface;
use Kna\AccountingBundle\Accounting\BaseEventProvider;

class DefaultEntryProvider extends BaseEntryProvider
{
    /**
     * {@inheritDoc}
     */
    public function supports($source): bool
    {
        return 
            $source instanceof PaymentEvent ||
            $source instanceof SaleEvent
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function createEntries(EventInterface $event): \Generator
    {
        if ($event instanceof PaymentEvent) {
            $entry = $this->createEntry();
            $entry->setAccount($event->getAccount());
            $entry->setAmount($event->getAmount());
            $entry->setDescription($this->translator->trans('event.payment.description', ['%event%' => (string) $event]));

            yield $entry;
        } elseif ($event instanceof SaleEvent) {
            $entry = $this->createEntry();
            $entry->setAccount($event->getOrder()->getAccount());
            $entry->setAmount($event->getAmount()->negative());
            $entry->setDescription($this->translator->trans('event.sale.description', ['%event%' => (string) $event, '%order%' => $event->getOrder()->getId()]));

            yield $entry;
        }
    }
}
```
## Usage

### Use event manager

```php
<?php
namespace App\Controller;

use App\Entity\Order;
use Kna\AccountingBundle\Accounting\EventManager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
    * @var EventManager
    */
    protected $eventManager;
    
    public function __construct(EventManager $eventManager) {
        $this->eventManager = $eventManager;
    }
    
    public function index(): Response
    {
        $order = new Order();
        $this->eventManager->processSource($order);
    }

}
```

### Use event processing command

```shell script
php bin/console kna_accounting:process-events --loop
```
