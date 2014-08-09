<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace hscstudio\heart\extensions;

use Yii;

/* load library TinyButStrong */
require Yii::getAlias('@hscstudio/heart').'/libraries/tbs/tbs_class.php';
require Yii::getAlias('@hscstudio/heart').'/libraries/tbs/plugins/tbs_plugin_opentbs.php';


/**
 * Usage in controllers
 *
```
// Initalize the TBS instance
	$TBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
	
	$template = Yii::getAlias('@hscstudio/heart').'/extensions/OpenTBS.template.docx';
	$TBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
	$TBS->VarRef['yourname']= "Employee";
	$data1 = [
				['col0' => 'No', 'col1' => 'A', 'col2' => 'B', 'col3' => 'C'],
			];
	$TBS->MergeBlock('a', $data1);
	
	$data2 = [
				['col0' => '1', 'col1' => 'A', 'col2' => 'AA', 'col3' => 'AAA'],
				['col0' => '2', 'col1' => 'B', 'col2' => 'BB', 'col3' => 'BBB'],
				['col0' => '3', 'col1' => 'C', 'col2' => 'CC', 'col3' => 'CCC'],
				['col0' => '4', 'col1' => 'D', 'col2' => 'DD', 'col3' => 'DDD'],
			];
	$TBS->MergeBlock('b', $data2);
	// Output the result as a file on the server.
	$TBS->Show(OPENTBS_DOWNLOAD, 'halo.docx'); // Also merges all [onshow] automatic fields.
	
	//exit;
``` 
 *
 * @author Hafid Mukhlasin <hafidmukhlasin@gmail.com>
 * @since 2.0
 */
 
class OpenTBS extends \clsTinyButStrong
{
    
    /**
     * Initializes the extension.
     */
    public function __construct()
	{
		// construct the TBS class
		parent::__construct();

		// load the OpenTBS plugin
		$this->Plugin(TBS_INSTALL, OPENTBS_PLUGIN); // load the OpenTBS plugin
		
		// hide error if any
		$this->NoErr;
	}
}
