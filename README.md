This is a simple PDO extension for [behat](http://behat.org).

# Install

``` bash
composer require behat/behat lixu/behat-pdo-extension --dev
```

# behat.yml

Add following configuration items to your ```behat.yml```.

``` yml
default:
  suites:
    default:
      path: %paths.base%/features
      contexts: 
        - FeatureContext
        - lixu\BehatPDOExtension\Context\PDOContext
  extensions:
    lixu\BehatPDOExtension:
      dsn: 'mysql:host=127.0.0.1;dbname=test;charset=UTF8'
      username: 'root'
      password: ''
```

# Write a scenario

Here is a simple scenario:

Note: You have to implement the "When I eat one apple" step by yourself.

```
  Scenario: Buy a product
    Given there are following "fruits":
      | name   | stock_level |
      | apple  | 10          |
      | orange | 5           |

    When I eat one apple

    Then there should be following "fruits":
      | name   | stock_level |
      | apple  | 9           |
      | orange | 5           |
```

# Use PDO in your feature class

If you want to use ```PDO``` in your feature class, you can simply extend your feature class from ```lixu\BehatPDOExtension\Context\PDOContext```, and get the PDO instance by ```$this->getPDO()```.
