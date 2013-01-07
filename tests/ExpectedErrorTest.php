<?php
class ExpectedErrorTest extends PHPUnit_Framework_TestCase
{
    /**
      @expectedException PHPUnit_Framework_Error
     */
    public function testEchecInclude()
    {
        include 'fichier_qui_n_existe_pas.php';
    }
}
?>
