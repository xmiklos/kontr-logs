<?php
/**
 * SystemLogsBuilder class
 * @package
 */

require_once "SystemLogsParser.php";
require_once "Request.php";
require_once "classes/PageBuilder.php";

/**
 * Class for generating system logs in html
 */
class SystemLogsBuilder extends PageBuilder
{

/**
 * array of strings (entries)
 * @var array
 */
public $log_entries;

/**
 * Constructor assigns given parameter request
 * And calls parse method on given parser
 * 
 * @param Request $request
 * @param SystemLogsParser $parser
 */
function __construct(Request $request, SystemLogsParser $parser)
{
	parent::__construct($request);
	
	$this->log_entries = $parser->parse();
}

/**
 * Method generates html table with log entries
 */
function show()
{
	echo "<table>";
	foreach($this->log_entries as $entry)
	{
		foreach($entry as $line)
		{
			echo "<tr>";
			$strip = htmlentities($line);
			echo "<td class='log_table_line' >{$strip}</td>";
			echo "</tr>";
		}
		echo "<tr><td class='log_table_separator' >&nbsp;</td></tr>";
	}
	echo "</table>";
}

/**
 * Method generates simple elements and imposes limit on number of displayed lines
 * 
 * @param string $content
 */
static function simple_show($content)
{
	$lines = explode("\n", $content);
	$i = 0;
	$nol = count($lines);
	$limit = 5000;
	if($nol > $limit)
	{
		$i = $nol - $limit;
	}
	
	ob_start();
	
	for( ;$i<$nol; $i++)
	{
		echo "<div>{$lines[$i]}</div>";
	}
	
	$contents = ob_get_contents();
	ob_end_clean();
	echo $contents;
	
}


}

?>
