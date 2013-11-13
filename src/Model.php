<?php

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
* "DATE" "(" SimpleArithmeticExpression ")". Modified from DoctrineExtensions\Query\Mysql\Year
*
* @category DoctrineExtensions
* @package DoctrineExtensions\Query\Mysql
* @author Rafael Kassner <kassner@gmail.com>
* @author Sarjono Mukti Aji <me@simukti.net>
* @license MIT License
*/
class Date extends FunctionNode
{
    public $date;

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return "DATE(" . $sqlWalker->walkArithmeticPrimary($this->date) . ")";
    }
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->date = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}

/**
* 
*/
class Model
{
	private static $em = null;
	private static $pdo = null;


	function __construct()
	{
	}

	public static function closePDO() {
		self::$pdo = null;		
	}

	public static function getPDO() {
		try {
			if(is_null(self::$pdo)) {
	            $user      = Config::singleton()->get("dbuser");
	            $password  = Config::singleton()->get("dbpass");
	            $dbname    = Config::singleton()->get("dbname");
	            $dbhost    = Config::singleton()->get("dbhost");				
	    		self::$pdo = new PDO('mysql:host='.$dbhost.';dbname='.$dbname, $user, $password);
		    }
		} catch (PDOException $e) {
		    die("Error in database connection");
		}
		return self::$pdo;
	}

	static function getEM() {
        if(is_null(self::$em)) {
	        // the connection configuration
	        $dbParams = array(
	            'driver'   => 'pdo_mysql',
	            'user'     => Config::singleton()->get("dbuser"),
	            'password' => Config::singleton()->get("dbpass"),
	            'dbname'   => Config::singleton()->get("dbname"),
	            'database_host' => Config::singleton()->get("dbhost"),
	        );

	        $config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array("app/models"), Config::singleton()->get("debug"));

	        self::$em = Doctrine\ORM\EntityManager::create($dbParams, $config);    

	        $emConfig = self::$em->getConfiguration(); 	
		    $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
		    $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
		    $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');	        
		    $emConfig->addCustomDatetimeFunction('Date', 'Date');	        
        }

		return self::$em;	
	}

	static function flush() {
		try {
			self::getEM()->flush();
			$result = true;
		} catch (Exception $e) {
			$result = false;
			self::getEM()->clear();
		}
		return $result;
	}

	static function runSQL($sql) {
		return self::getPDO()->query($sql);
	}

	static function flushMsg($msgSuccess = "Success",$msgFail = "Fail", $msgViewer = "FlashMsg") {
		if(self::flush())
			$msgViewer::add(MsgType::Successful, $msgSuccess);
		else
			$msgViewer::add(MsgType::Error, $msgFail);
	}
}

?>