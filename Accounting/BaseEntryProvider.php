<?php
namespace Kna\AccountingBundle\Accounting;


use Kna\AccountingBundle\Model\EntryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseEntryProvider implements EntryProviderInterface
{
    /**
     * @var string
     */
    protected $entryClass;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @return string
     */
    public function getEntryClass(): string
    {
        return $this->entryClass;
    }

    /**
     * @param string $entryClass
     */
    public function setEntryClass(string $entryClass): void
    {
        $this->entryClass = $entryClass;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }

    /**
     * @return EntryInterface
     */
    protected function createEntry(): EntryInterface
    {
        return new $this->entryClass();
    }

}