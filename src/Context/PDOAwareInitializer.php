<?php

namespace lixu\BehatPDOExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;

class PDOAwareInitializer implements ContextInitializer
{

    /**
     * The app kernel.
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * The Behat context.
     *
     * @var Context
     */
    private $context;

    /**
     * Construct the initializer.
     *
     * @param HttpKernelInterface $kernel
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function initializeContext(Context $context)
    {
        $this->context = $context;

        if ($context instanceof PDOAwareContext) {
            $context->setPDO($this->pdo);
        }
    }
}
