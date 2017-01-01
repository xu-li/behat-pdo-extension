<?php
namespace lixu\BehatPDOExtension\Context;

use Behat\Behat\Context\Context;

interface PDOAwareContext extends Context
{
    /**
     * Set PDO instance on the Context
     *
     * @param PDO $pdo
     * @return mixed
     */
    public function setPDO(\PDO $pdo);

    /**
     * Get PDO instance on the Context
     *
     * @return PDO
     */
    public function getPDO();
}
