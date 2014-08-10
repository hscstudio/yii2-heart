<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{

	<?php if($_POST['Generator']['layout']=='column2'){ ?>
	public $layout = '@hscstudio/heart/views/layouts/column2';
	<?php } else { ?>
	public $layout = '@hscstudio/heart/views/layouts/column1';
	<?php } ?> 
	
	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
        ]);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', <?= $urlParams ?>]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
        $currentFiles=[];
        <?php
        $idx2=0;
        if (($tableSchema = $generator->getTableSchema()) !== false) {
            foreach ($tableSchema->columns as $column) {
                if ($column->phpType == 'string' && $column->size === 255 && 
                    preg_match('/^(document|document1|document2|photo|image|picture|file)$/i', $column->name)) {
                    ?>
        $currentFiles[<?= $idx2; ?>]=$model-><?= $column->name ?>;
                    <?php
                    $idx2++;
                }
            }
        }
        ?>

        if ($model->load(Yii::$app->request->post())) {
            $files=[];
			<?php
			$idx=0;
			if (($tableSchema = $generator->getTableSchema()) !== false) {
				foreach ($tableSchema->columns as $column) {
					if ($column->phpType == 'string' && $column->size === 255 && 
						preg_match('/^(document|document1|document2|photo|image|picture|file)$/i', $column->name)) {
						?>					
				$files[<?= $idx; ?>] = \yii\web\UploadedFile::getInstance($model, '<?= $column->name ?>');
				$model-><?= $column->name ?>=isset($currentFiles[<?= $idx; ?>])?$currentFiles[<?= $idx; ?>]:'';
				if(!empty($files[<?= $idx; ?>])){
					$ext = end((explode(".", $files[<?= $idx; ?>]->name)));
					$model-><?= $column->name ?> = uniqid() . '.' . $ext;
					$path = '';
					if(isset(Yii::$app->params['uploadPath'])){
						$path = Yii::$app->params['uploadPath'].'/<?= strtolower($modelClass); ?>/'.$model->id.'/';
					}
					else{
						$path = Yii::getAlias('@common').'/../files/<?= strtolower($modelClass); ?>/'.$model->id.'/';
					}
					@mkdir($path, 0777, true);
					@chmod($path, 0777);
					$paths[<?= $idx; ?>] = $path . $model-><?= $column->name ?>;
					if(isset($currentFiles[<?= $idx; ?>])) @unlink($path . $currentFiles[<?= $idx; ?>]);
				}
						<?php
						$idx++;
					}
				}
			}
            ?>

            if($model->save()){
				$idx=0;
                foreach($files as $file){
					if(isset($paths[$idx])){
						$file->saveAs($paths[$idx]);
					}
					$idx++;
				}
                return $this->redirect(['view', <?= $urlParams ?>]);
            } else {
                // error in saving model
            }            
        }
		else{
			//return $this->render(['update', <?= $urlParams ?>]);
			return $this->render('update', [
                'model' => $model,
            ]);
		}
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        $this->findModel(<?= $actionParams ?>)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionEditable() {
		$model = new <?= $modelClass ?>; // your model can be loaded here
		// Check if there is an Editable ajax request
		if (isset($_POST['hasEditable'])) {
			// read your posted model attributes
			if ($model->load($_POST)) {
				// read or convert your posted information
				$model2 = $this->findModel($_POST['editableKey']);
				$name=key($_POST['<?= $modelClass ?>'][$_POST['editableIndex']]);
				$value=$_POST['<?= $modelClass ?>'][$_POST['editableIndex']][$name];
				$model2->$name = $value ;
				$model2->save();
				// return JSON encoded output in the below format
				echo \yii\helpers\Json::encode(['output'=>$value, 'message'=>'']);
				// alternatively you can return a validation error
				// echo \yii\helpers\Json::encode(['output'=>'', 'message'=>'Validation error']);
			}
			// else if nothing to do always return an empty JSON encoded output
			else {
				echo \yii\helpers\Json::encode(['output'=>'', 'message'=>'']);
			}
		return;
		}
		// Else return to rendering a normal view
		return $this->render('view', ['model'=>$model]);
	}

	public function actionOpenTbs($filetype='docx'){
		$dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);
		
		try {
			$templates=[
				'docx'=>'ms-word.docx',
				'odt'=>'open-document.odt',
				'xlsx'=>'ms-excel.xlsx'
			];
			// Initalize the TBS instance
			$OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
			// Change with Your template kaka
			$template = Yii::getAlias('@hscstudio/heart').'/extensions/opentbs-template/'.$templates[$filetype];
			$OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
			$OpenTBS->VarRef['modelName']= "<?= $modelClass ?>";
<?php		
$idx=0;
foreach ($generator->getColumnNames() as $name) {?>
			$data1[]['col<?= $idx; ?>'] = '<?= $name; ?>';			
<?php
if(++$idx>3) break;
}
?>	
			$OpenTBS->MergeBlock('a', $data1);			
			$data2 = [];
			foreach($dataProvider->getModels() as $<?= strtolower($modelClass); ?>){
				$data2[] = [
					'col0'=>$<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[0] ?>,
					'col1'=>$<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[1] ?>,
					'col2'=>$<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[2] ?>,
					'col3'=>$<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[3] ?>,
				];
			}
			$OpenTBS->MergeBlock('b', $data2);
			// Output the result as a file on the server. You can change output file
			$OpenTBS->Show(OPENTBS_DOWNLOAD, 'result.'.$filetype); // Also merges all [onshow] automatic fields.			
			exit;
		} catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}	
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);		
	}	
	
	public function actionPhpExcel($filetype='xlsx',$template='yes',$engine='')
    {
		$dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);
		
		try {
			if($template=='yes'){
				// only for filetypr : xls & xlsx
				if(in_array($filetype,['xlsx','xls'])){
					$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
					$objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
					$template = Yii::getAlias('@hscstudio/heart').'/extensions/phpexcel-template/ms-excel.'.$filetype;
					$objPHPExcel = $objReader->load($template);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
					$objPHPExcel->getProperties()->setTitle("PHPExcel in Yii2Heart");
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A1', 'Tabel <?= $modelClass; ?>');
					$idx=2; // line 2
					foreach($dataProvider->getModels() as $<?= strtolower($modelClass); ?>){
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[0] ?>)
													  ->setCellValue('B'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[1] ?>)
													  ->setCellValue('C'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[2] ?>)
													  ->setCellValue('D'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[3] ?>);
						$idx++;
					}		
					
					// Redirect output to a client’s web browser
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="result.'.$filetype.'"');
					header('Cache-Control: max-age=0');
					$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
					$objWriter->save('php://output');
					exit;
				}
				else{
					Yii::$app->session->setFlash('error', 'Unfortunately pdf not support, only for excel');
				}
			}
			else{
				if(in_array($filetype,['xlsx','xls'])){
					$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
					// Create new PHPExcel object
					$objPHPExcel = new \PHPExcel();
					$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
					$objPHPExcel->getProperties()->setTitle("PHPExcel in Yii2Heart");
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A1', 'Tabel <?= $modelClass; ?>');
					$idx=2; // line 2
					foreach($dataProvider->getModels() as $<?= strtolower($modelClass); ?>){
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[0] ?>)
													  ->setCellValue('B'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[1] ?>)
													  ->setCellValue('C'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[2] ?>)
													  ->setCellValue('D'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[3] ?>);
						$idx++;
					}		
									
					// Redirect output to a client’s web browser (Excel2007)
					if($filetype=='xlsx')
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					// Redirect output to a client’s web browser (Excel5)
					if($filetype=='xls')
					header('Content-Type: application/vnd.ms-excel');

					header('Content-Disposition: attachment;filename="result.'.$filetype.'"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');

					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
					$objWriter->save('php://output');
					exit;				
				}
				else if(in_array($filetype,['pdf'])){
					if(in_array($engine,['tcpdf','mpdf',''])){
						$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
						if($engine=='tcpdf' or $engine==''){
							$rendererName = \PHPExcel_Settings::PDF_RENDERER_TCPDF;
							$rendererLibraryPath = Yii::getAlias('@hscstudio/heart').'/libraries/tcpdf';
						}
						else if($engine=='mpdf'){
							$rendererName = \PHPExcel_Settings::PDF_RENDERER_MPDF;
							$rendererLibraryPath = Yii::getAlias('@hscstudio/heart').'/libraries/mpdf';
						}
						// Create new PHPExcel object
						$objPHPExcel = new \PHPExcel();
						
						$objPHPExcel->getProperties()->setTitle("PHPExcel in Yii2Heart");
						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue('A1', 'Tabel <?= $modelClass; ?>');
						$idx=2; // line 2
						foreach($dataProvider->getModels() as $<?= strtolower($modelClass); ?>){
							$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[0] ?>)
														  ->setCellValue('B'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[1] ?>)
														  ->setCellValue('C'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[2] ?>)
														  ->setCellValue('D'.$idx, $<?= strtolower($modelClass); ?>-><?= $generator->getColumnNames()[3] ?>);
							$idx++;
						}		
						
						if (!\PHPExcel_Settings::setPdfRenderer(
							$rendererName,
							$rendererLibraryPath
						)){
							Yii::$app->session->setFlash('error', 
								'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
								'<br />' .
								'at the top of this script as appropriate for your directory structure'
							);
						}
						else{
							// Redirect output to a client’s web browser (PDF)
							header('Content-Type: application/pdf');
							header('Content-Disposition: attachment;filename="result.pdf"');
							header('Cache-Control: max-age=0');

							$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
							$objWriter->save('php://output');
							exit;
						}
					}
					else{
						Yii::$app->session->setFlash('error', 'Unfortunately this engine not support');
					}
				}
				else{
					Yii::$app->session->setFlash('error', 'Unfortunately filetype not support, only for excel & pdf');
				}
			}
        } catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}	
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);	
    }
	
	public function actionImport(){
		$dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass; ?>::find(),
        ]);
		
		/* 
		Please read guide of upload https://github.com/yiisoft/yii2/blob/master/docs/guide/input-file-upload.md
		maybe I do mistake :)
		*/		
		if (!empty($_FILES)) {
			$importFile = \yii\web\UploadedFile::getInstanceByName('importFile');
			if(!empty($importFile)){
				$fileTypes = ['xls','xlsx']; // File extensions allowed
				//$ext = end((explode(".", $importFile->name)));
				$ext=$importFile->extension;
				if(in_array($ext,$fileTypes)){
					$inputFileType = \PHPExcel_IOFactory::identify($importFile->tempName );
					$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($importFile->tempName );
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$baseRow = 2;
					$inserted=0;
					$read_status = false;
					$err=[];
					while(!empty($sheetData[$baseRow]['A'])){
						$read_status = true;
						$abjadX=array();
						<?php
						for($i=65;$i<=90;$i++){
							$abjadX[]=chr($i);
							if($i==90){
								for($j=65;$j<=90;$j++){
									for($k=65;$k<=90;$k++){
										$abjadX[]=chr($j).chr($k);
									}
								}
							}
						}
						$count=0;
						foreach ($generator->getTableSchema()->columns as $column) {
							if($count!=0) echo "\t\t\t\t\t\t";
							if ($column->autoIncrement or in_array($column->name, ['created','createdBy','modified','modifiedBy','deleted','deletedBy'])) 
								echo "//\$" . $column->name . "=  \$sheetData[\$baseRow]['".$abjadX[$count]."'];\n";	
							else 
								echo "\$" . $column->name . "=  \$sheetData[\$baseRow]['".$abjadX[$count]."'];\n";
							$count++;
						}
						echo "\n";
						?>
						$model2=new <?= $modelClass; ?>;
						<?php
						$count=0;
						foreach ($generator->getTableSchema()->columns as $column) {
							if($count!=0) echo "\t\t\t\t\t\t";
							if ($column->autoIncrement or in_array($column->name, ['created','createdBy','modified','modifiedBy','deleted','deletedBy'])) 
								echo "//\$model2->" . $column->name . "=  \$".$column->name.";\n";
							else 
								echo "\$model2->" . $column->name . "=  \$".$column->name.";\n";
							$count++;	
						}
						echo "\n";
						?>
						try{
							if($model2->save()){
								$inserted++;
							}
							else{
								foreach ($model2->errors as $error){
									$err[]=($baseRow-1).'. '.implode('|',$error);
								}
							}
						}
						catch (\yii\base\ErrorException $e){
							Yii::$app->session->setFlash('error', "{$e->getMessage()}");
							//$this->refresh();
						} 
						$baseRow++;
					}	
					Yii::$app->session->setFlash('success', ($inserted).' row inserted');
					if(!empty($err)){
						Yii::$app->session->setFlash('warning', 'There are error: <br>'.implode('<br>',$err));
					}
				}
				else{
					Yii::$app->session->setFlash('error', 'Filetype allowed only xls and xlsx');
				}				
			}
			else{
				Yii::$app->session->setFlash('error', 'File import empty!');
			}
		}
		else{
			Yii::$app->session->setFlash('error', 'File import empty!');
		}
		
		return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);					
	}
}
