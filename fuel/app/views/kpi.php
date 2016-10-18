<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/mail/index/<?=$oKpi->MAIL_ID;?>">Mail</a></li>
  <li class="active">Kpi</li>
</ol>

<section id="mainWrapper">
<form class="form-horizontal" role="form" method="POST" id="kpiForm" action="/kpi/save/<?=$oKpi->MAIL_ID;?>/<?=$oKpi->KPI_ID;?>">
  <div class="row form-group">
    <label for="name" class="col-md-1 control-label">Kpi name</label>
    <div class="col-md-4">
      <input name="NAME" class="form-control" placeHolder="Kpi name" value="<?=$oKpi->NAME;?>">
    </div>

    <label for="name" class="col-md-2 control-label">Connexion</label>
    <div class="col-md-4">
      <?=$sConnexionSelect;?>
    </div>
  </div>

  <div class="row form-group">
    <label for="name" class="col-md-1 control-label">Mode</label>
    <div class="col-md-4">
      <?=$sModeSelect;?>
    </div>

    <label for="name" class="col-md-2 control-label">Grap mode</label>
    <div class="col-md-4">
      <?=$sGraphModeSelect;?>
    </div>
  </div>
  <div class="row form-group">
	  <label for="name" class="col-md-1 control-label">Excluded hours</label>
	  <div class="col-md-10 excludedHours">
		<?=$sExcludedHours?>
	</div>
  </div>

  <div class="row form-group">
  	<div class="col-md-offset-1 col-md-10">
  		<div class="alert alert-warning" style="padding:3px 10px; margin-bottom:5px;">For graph, only first column can be a string, the others must be integer or floating values. In Pie mode, only first 2 columns are used.</div>
  	</div>
  	<div class="col-md-offset-1 col-md-5">
  	 	<label for="query" class="control-label">Query</label>
  		<div class="form-control console query" id="query" style="height:220px;width:100%"><?=htmlentities($oKpi->QUERY);?></div>
  	</div>
  	<div class="col-md-5">
  		<label for="query_compare" class="control-label">Comparing query</label>
      <div class="form-control console query" id="query_compare" style="height:220px;width:100%"><?=htmlentities($oKpi->QUERY_COMPARE);?></div>
    </div>
    <div class="col-md-offset-1 col-md-10">
  		<div class="alert" style="background-color:#F5F5F5;border:1px solid #ccc; padding:3px 10px; margin-top:5px;margin-bottom:0px;">
      	Available preset variables:
      	<span class="label label-primary" style="font-family:monospace;padding:2px;">:mtd_start_[0-1]</span>
      	<span class="label label-primary" style="font-family:monospace;padding:2px;">:mtd_end_[0-1]</span>
      	<span class="label label-primary" style="font-family:monospace;padding:2px;">:wtd_start_[0-1]</span>
      	<span class="label label-primary" style="font-family:monospace;padding:2px;">:wtd_end_[0-1]</span>
      	<span class="label label-primary" style="font-family:monospace;padding:2px;">:date_[0-9]</span>
      	<span class="label label-primary" style="font-family:monospace;padding:2px;">:bimonthly_start</span>
      	<span class="label label-primary" style="font-family:monospace;padding:2px;">:bimonthly_end</span>
      	<span class="label label-primary" style="font-family:monospace;padding:2px;">:monthly_start</span>
      	<span class="label label-primary" style="font-family:monospace;padding:2px;">:monthly_end</span>
      </div>
  	</div>
  	<div class="col-md-offset-1 col-md-10">
  		<input name="JSON_TEST_PARAM" class="form-control" placeHolder="Test parameters (json format)" style="margin-top:5px;" value="<?=$oKpi->JSON_TEST_PARAM;?>">
  	</div>
  </div>

  <div class="row form-group">
    <label for="group" class="col-md-1 control-label">Group on</label>
    <div class="col-md-4">
      <input name="GROUP" class="form-control" placeHolder="group on" value="<?=$oKpi->GROUP;?>">
    </div>

    <label for="group" class="col-md-2 control-label">Priority (int)</label>
    <div class="col-md-4">
      <input name="PRIORITY" class="form-control" placeHolder="group on" value="<?=$oKpi->PRIORITY;?>">
    </div>
  </div>

  <div class="row form-group">
    <label for="DESCRIPTION" class="col-md-1 control-label">Description</label>
    <div class="col-md-10">
      <input name="DESCRIPTION" class="form-control" placeHolder="Description" value="<?=$oKpi->DESCRIPTION;?>">
    </div>
  </div>

  <div class="row form-group">
    <label for="ATTACH_CSV" class="col-md-1 control-label">Attach CSV</label>
    <div class="col-md-4">
      <input name="ATTACH_CSV" id="ATTACH_CSV" type="checkbox" value="1" <?if($oKpi->ATTACH_CSV == 1) echo 'checked="checked"';?>">
    </div>

    <label for="DISPLAY_QUERIES" class="col-md-2 control-label">Display queries</label>
    <div class="col-md-1">
      <input name="DISPLAY_QUERIES" id="DISPLAY_QUERIES" type="checkbox" value="1" <?if($oKpi->DISPLAY_QUERIES == 1) echo 'checked="checked"';?>">
    </div>
    
    <label for="DIFF_PERCENT" class="col-md-1 control-label">Diff in percent</label>
    <div class="col-md-2">
      <input name="DIFF_PERCENT" id="DIFF_PERCENT" type="checkbox" value="1" <?if($oKpi->DIFF_PERCENT == 1) echo 'checked="checked"';?>">
    </div>
  </div>

  <div class="row form-group">
    <label for="IS_ACTIVE" class="col-md-1 control-label">Active</label>
    <div class="col-md-4">
      <input name="IS_ACTIVE" id="IS_ACTIVE" type="checkbox" value="1" <?if($oKpi->IS_ACTIVE == 1) echo 'checked="checked"';?>">
    </div>
    
    <label for="INVERT_COLOR" class="col-md-2 control-label">Invert color</label>
    <div class="col-md-1">
      <input name="INVERT_COLOR" id="INVERT_COLOR" type="checkbox" value="1" <?if($oKpi->INVERT_COLOR == 1) echo 'checked="checked"';?>">
    </div>
    
    <label for="FORMAT_INTEGER" class="col-md-1 control-label">Format integers</label>
    <div class="col-md-2">
      <input name="FORMAT_INTEGER" id="FORMAT_INTEGER" type="checkbox" value="1" <?if($oKpi->FORMAT_INTEGER == 1) echo 'checked="checked"';?>">
    </div>
  </div>

  <input type="hidden" name="QUERY" value="" />
  <input type="hidden" name="QUERY_COMPARE" value="" />

	<div class="row form-group">
		<div class="col-md-offset-1 col-md-10">
		<button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> <?php if($oKpi->KPI_ID > 0):?>Update<?php else:?>Save<?php endif;?></button>
		<a href="#preview" class="btn btn-warning kpiPreview"><span class="fa fa-search"></span> Preview</a>
		<a href="/mail/index/<?=$oKpi->MAIL_ID;?>" class="btn btn-primary"><span class="fa fa-close"></span> Close</a>
		</div>
	</div>
</form>
<br>

<div class="col-sm-offset-1 col-sm-10" style="padding-left:0; padding-right:0;">
	<div id="preview"></div>
</div>
</section>

<script type="text/javascript">
<!--
aceEditor();
-->
</script>