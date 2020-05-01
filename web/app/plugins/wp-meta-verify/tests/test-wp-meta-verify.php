<?php 

class WP_Meta_VerifyTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->class_instance = new WP_Meta_Verify(); //Name of the plugin class
    }


    public function test_google_site_verification()
	{
	    $meta_tag = $this->class_instance->google_site_verification('B6wFaCRbzWE42SyxSvKUOyyPxZfJCb5g');
	    $expected = '<meta name="google-site-verification" content="B6wFaCRbzWE42SyxSvKUOyyPxZfJCb5g">';

	    $this->assertEquals($expected, $meta_tag);
	}

    public function test_bing_site_verification()
	{
	    $meta_tag = $this->class_instance->bing_site_verification('B6wFaCRbzWE42SyxSvKUOyyPxZfJCb5g');
	    $expected = '<meta name="msvalidate.01" content="B6wFaCRbzWE42SyxSvKUOyyPxZfJCb5g">';

	    $this->assertEquals($expected, $meta_tag);
	}

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
}