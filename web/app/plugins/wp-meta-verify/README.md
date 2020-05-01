
# wp-meta-verify
![travis ci build passing](https://travis-ci.com/pablorica/wp-meta-verify.svg?branch=master)
This is a fake plugin to test Travis CI


## Updating `.travis.ylm`
Adding comments to each section and adding Job names

```
matrix:
  include:
    - name: Coding standards
      php: 7.3
      env: WP_VERSION=latest

    # Canary for our oldest-supported version
    - name: Legacy
      php: 5.6
      env: WP_VERSION=4.9

    # Nightly versions of PHP and WordPress.
    - name: Bleeding edge
      php: 7.4snapshot
      env: WP_VERSION=trunk
``` 

## Creating more tests
```php
    public function testQuantityTransformIsCorrect() {
	    $result = $this->class_instance->get_quantity( '1.000 €' );
	    $this->assertEquals( 1000, $result );
	}
    public function testVatOfIntegerIsCorrect() {
	    $result = $this->class_instance->get_vat( 1000 );
	    $this->assertEquals( 210, $result );
	}
	  
	public function testVatOfStringIsCorrect() {
	    $result = $this->class_instance->get_vat( '1.000 €' );
	    $this->assertEquals( 210, $result );
	}
```
## Updating `.travis.ylm`

Original:

```
matrix:
  include:
    - php: 7.2
      env: WP_VERSION=latest
    - php: 7.1
      env: WP_VERSION=latest
    - php: 7.0
      env: WP_VERSION=latest
    - php: 5.6
      env: WP_VERSION=latest
    - php: 5.6
      env: WP_VERSION=trunk
    - php: 5.6 <- Failed build 
      env: WP_TRAVISCI=phpcs
    - php: 5.3 <- Failed build 
      env: WP_VERSION=latest
      dist: precise
``` 
Update:

```
matrix:
  include:
    - php: 7.1
      env: WP_VERSION=latest
    - php: 7.0
      env: WP_VERSION=latest
    - php: 5.6
      env: WP_VERSION=latest
    - php: 5.6
      env: WP_VERSION=trunk
``` 

## Creating repository
```console
$ cd /srv/www/camisetas.codigo.co.uk/current/web/app
$ git clone https://github.com/pablorica/wp-meta-verify