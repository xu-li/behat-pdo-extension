<?php
namespace lixu\BehatPDOExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;

use lixu\BehatPDOExtension\Exception\RecordsNotFoundException;

class PDOContext implements Context, PDOAwareContext
{
    protected $pdo;

    /**
     * Set PDO instance on the Context
     *
     * @param PDO $pdo
     * @return mixed
     */
    public function setPDO(\PDO $pdo)
    {
        $this->pdo = $pdo;

        return $this;
    }

    /**
     * Get PDO instance on the Context
     *
     * @return PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }

    /**
     * Checks records in database
     * Example: Given there are following "users":
     *              | username      | role  |
     *              | administrator | admin |
     *
     * Example: Given there are following "test.users":
     *              | username      | role  |
     *              | administrator | admin |
     *
     * @Given there are following :table:
     * @Then there should be following :table:
     */
    public function recordsExist($table, TableNode $records)
    {
        $pdo = $this->getPDO();

        // Check input
        if (empty($table) || empty($records->getTable())) {
            return ;
        }

        $rows     = $records->getRows();
        $columns  = array_shift($rows);
        $num_rows = count($rows);

        if (empty($columns) || 0 === $num_rows) {
            return ;
        }

        // Prepare statements
        $select_statement = $this->prepareSelectQuery($table, $columns, $num_rows);

        // Process all the records
        $i = 1;
        foreach ($rows as $row) {
            // Bind values
            foreach ($row as $value) {
                $select_statement->bindValue($i++, $value);
            }
        }

        // Execute
        $select_statement->execute();

        // Check rows
        if ($num_rows != $select_statement->fetchColumn()) {
            throw new RecordsNotFoundException("There are no such {$table}.", $table);
        }
    }

    /**
     * Prepare a select query
     *
     * @param string $table
     * @param array $columns
     * @param integer $num_rows
     * @return PDOStatement
     */
    protected function prepareSelectQuery($table, $columns, $num_rows)
    {
        // Quote identifiers
        $quoted_tbl  = $this->quoteIdentifier($table);
        $quoted_cols = array_map([$this, 'quoteIdentifier'], $columns);

        $where = implode(' = ? AND ', $quoted_cols) . ' = ?';
        $where = array_fill(0, $num_rows, $where);
        $where = '(' . implode(') OR (', $where) . ')';

        return $this->getPDO()->prepare("SELECT COUNT(*) FROM {$quoted_tbl} WHERE {$where}");
    }

    /**
     * Quote the identifier
     *
     * @param string $field
     * @return string
     */
    protected function quoteIdentifier($field)
    {
        // if there is a dot in between, assume it's a db.table string
        if (strpos($field, '.') !== false) {
            list($db, $table) = explode('.', $field, 2);
            return $this->quoteIdentifier($db) . '.' . $this->quoteIdentifier($table);
        }

        return "`" . str_replace("`", "``", $field) . "`";
    }
}
