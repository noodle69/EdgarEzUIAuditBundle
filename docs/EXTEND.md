# EdgarEzUIAuditBundle

## Extend

You can declare new Audit "Slot" to log custom signal. 

### Define your Audit class

In your bundle (AcmeBundle), create a **Audit** folder.

In this **Audit** folder, create a folder defining audit group, example: **Foo**

In this last folder, create a new class.

```php
namespace AcmeBundle\Audit\Foo;

use Edgar\EzUIAudit\Audit\AbstractAudit;
use eZ\Publish\Core\SignalSlot\Signal;

class <YourAudit>Audit extends AbstractAudit
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof <Signal\Class\Path>l
            || !$this->auditService->isConfigured(self::class)
        ) {
            return;
        }

        $this->infos = [
            '<key>' => $signal-><key>,
            // ...
        ];

        $this->auditService->log($this);
    }
}
```

* <YourAudit>: name your audit class as your signal
* <Signal\Class\Path>: your signal class path
* <key>: signal information to be logged

### Register this new Audit as service

in a Resources/config/audits.yml, add

```yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true
        public: false

    AcmeBundle\Audit\Foo\<YourAudit>Audit:
        tags:
            - { name: ezpublish.api.slot, signal: <Your\Signal> }
```
