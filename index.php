<?php
/**
 * 
 *
 * 
 * 
 * 
 *
 * PHP version 5.5+
 *
 * @category	###Framework###
 * @package		###Index###
 * @author		Joseph
 * @copyright 	Joseph
 * @license		Joseph
 * @version		
 * @since 		2017-06-25
 */
error_reporting(E_ERROR | E_PARSE);
require_once('config/config.inc.php');
require_once($CFG['site']['project_path'].'commons/class_common.php');
//---------------------------------------------------------------------------// 
class Index extends CommonClass
	{
		/**
		 * To get Top Referesh
		 */
		public function getRefresh()
			{
				$i = 1;
				foreach($this->CFG['site']['grid']['home'] as $key=>$val)
					{
					?>
						<li><span>Garden<?php echo $i?></span><a href="javascript:void(0)" id="refreshrecord<?php echo $i;?>" onclick="refreshrecords('<?php echo $val;?>');">REFRESH</a></li>
					<?php
						$i++; 
					}
			}

		/**
		 * To get Grid head
		 * @param $link_id 
		 */
		public function getGridHead($link_id,$val)
			{	
				?>
				<div class=" col-md-4 col-sm-4 col-xs-12">
					<div class="tab-top-sec">		 
						<span class="title-gar">Garden<?php echo $link_id;?></span>
						<button class="links-btn" value="Link" onclick="loadallrecords('<?php echo $link_id;?>');" >Link</button>			
						<div class="serch-sec">
							<input class="serch-input" type="text" placeholder="Plant Name" name="plantname<?php echo $link_id;?>" id="plantname<?php echo $link_id;?>" />
							<input type="button" class="serch-btn" value="Search Plant" onclick="loaddata('<?php echo $val;?>','<?php echo $link_id;?>',$('#plantname<?php echo $link_id;?>').val());" />
						</div>
					</div>		
					<div id="<?php echo $val;?>">						
				<?php 
			}
			
		/**
		 * To load Grid footer
		 */
		public function getGridFooter()
			{
				?>		
					</div>
				</div>
				<?php 	
			}

		/**
		 * To get data from tabel
		 */
		public function getDataFromTable()
			{	
				$link_id = 1;				
				foreach($this->CFG['site']['grid']['home'] as $key=>$val)
					{
						$row = $this->getTabelRecords($val,$this->CFG['site']['home']['limit']);						
						$this->getGrid($row,$val,$link_id);
						$link_id++;
					}
			}	
			
		/**
		 * To Load the grid data
		 * @param $link_id
		 * @param $mydata
		 * @param $msg 
		 */
		public function loadGridData($link_id,$mydata,$msg=false)
			{
				?>
				<table class="table table-responsive table-bordered scroll">
					<thead>
						<tr>
							<th class="th-head">Plant</th>
							<th class="th-head2">Title</th>
							<th class="th-head3">Note</th>
							<th class="th-head4">Link </th>
						</tr>
					</thead>
					<tbody class="testDiv2"> 
				<?php 
				if($msg)
					{
				?>
						<tr><td class="th-head"><?php echo $msg;?></td></tr>
				<?php 		
					}
				else
					{	
						$x=1;
						foreach ($mydata as $myresult)
				 			{ 	
				 			?>
								<tr>
									<td class="th-head"><?php echo $myresult['plant_code'];?></td>
					                <?php
					                $y1 = '1_'.$link_id;
					                ?>                
									<td class="th-head2">
										<?php  echo preg_replace($this->CFG['site']['home']['desc_pattern'], ' ', substr($myresult['description'], 0, $this->CFG['site']['home']['desc'])).'&nbsp;&nbsp;<a  href="javascript:void(0)" class="btnShow1" data= '.$link_id.' />... </a>';?>
									</td>
									<div class="dialog1<?php echo $y1; ?>" style="display: none;text-align: center;">
		                				<?php echo preg_replace($this->CFG['site']['home']['desc_pattern'], ' ', $myresult['description']); ?>
		                			</div>
									<?php									
										$x1 = '1_'.$x;
		 							?>
									<td class="th-head3">
										<?php echo preg_replace($this->CFG['site']['home']['desc_pattern'], ' ', substr($myresult['description'], 0, $this->CFG['site']['home']['desc'])).'&nbsp;&nbsp;<a  href="javascript:void(0)" class="btnShow" data= '.$link_id.' />... </a>';   ?>
									</td>
									<td class="th-head4"><a target="_blanck" href="<?php echo $myresult['link'];?>">link</a></td>
								</tr>
								<div class="dialog<?php echo $x1; ?>" style="display: none;text-align: center;">
		    						<?php echo preg_replace($this->CFG['site']['home']['desc_pattern'], ' ', $myresult['description']); ?>
								</div>	
							<?php 
				        	$x++; 
				 			}
					}			
		 		?>
		 			</tbody>
				</table>
		 		<?php 
			}	
			
		/**
		 * To load the grid
		 * @param $mydata
		 * @param $val
		 * @param $link_id
		 */
		public function getGrid($mydata,$val,$link_id)
			{	
				$this->getGridHead($link_id,$val);				
		  		$this->loadGridData($link_id,$mydata);
				$this->getGridFooter();
			}

		/**
		 * To get Ajax search record
		 */
		public function getAjaxSearchPlanet()
			{
				$where_cond = false;
				if($this->fields_arr['keywords'])
					$where_cond = 'plant_code LIKE \'%'.$this->fields_arr['keywords'].'%\' || description LIKE \'%'.$this->fields_arr['keywords'].'%\'';
				$row = $this->getTabelRecords($this->fields_arr['table_name'],$this->CFG['site']['home']['limit'],false,'LAST_UPDT_ON','DESC',$where_cond);
				if($row){
					$this->loadGridData($this->fields_arr['grid_count'],$row);
				}	
				else{
					$this->loadGridData($this->fields_arr['grid_count'],false,"No Record Found");	
				}	
			}	
	}
//---------------------------------index end------------------->>>>>>//
$index = new Index();
$index->setFormField('table_name','');
$index->setFormField('keywords','');
$index->setFormField('grid_count','');
$index->setFormField('grid_table_names',implode(",",$CFG['site']['grid']['home']));
$index->sanitizeFormInputs($_REQUEST);
//Header Include
getMetaTags('metatitle','Home');
getMetaTags('metadescription','Home'); 
getMetaTags('metakeyword','Home');
if($index->isFormPOSTed($_POST,'table_name'))
{
	$index->getAjaxSearchPlanet();
	exit();
}
//------------------------------------HTML code start from here -------->>>>>>>>//
//Headr include
require_once($CFG['site']['project_path'].'includes/header.php'); 
?>
<?php /* Top referesh start from here*/?> 
<div class="header">
	<div class="container">
		<div class="logo col-md-12 col-sm-12 col-xs-12 text-center">
			<a href="#"><img src="images/logo.png"/></a>
		</div>
		<div class="menu-bar-sec col-md-12 col-sm-12 col-xs-12 text-center">
			<nav class="navbar">
				<ul class="dis-inline">
					 <?php $index->getRefresh();?>
				</ul>
			</nav>
		</div>
	</div>
</div>
<?php /* Load the grid*/?> 
<div class="main">
	<div class="container1">
		<?php $index->getDataFromTable();?>
	</div>
</div>
<?php /* assign table names*/?>
<script type="text/javascript">
<!--
var tabel_name = '<?php echo $index->getFormField('grid_table_names');?>'
//-->
</script>	
<?php
//Include Footer
require_once($CFG['site']['project_path'].'includes/footer.php');
?>

