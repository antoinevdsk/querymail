<?php if($aQueryData):?>
	<?php $i=0;?>
	
	<table cellpadding="5" cellspacing="0" border="1" width="100%" style="font-family: Arial, Helvetica, sans-serif; border: 1px solid #000; border-collapse: collapse;">
	<?php if(empty($oKpi->GROUP)):?>
	
		<?php foreach ($aQueryData as $iKey => $aData):?>
			<?php if($i==0):?>
				<tr>
				<?php foreach ($aData as $sField => $mData):?>
					<th bgcolor="#CCCCCC"><font size="2"><?=$sField;?></font></th>
				<?php endforeach;?>
				</tr>
			<?php endif;?>
			<?php $i++;?>
			<tr>
			<?php foreach ($aData as $sField => $mData):?>
				<td bgcolor="#FFFFFF">
					<?php
					if(is_numeric($mData) && !empty($oKpi->QUERY_COMPARE)){
						if(isset($aQueryDataCompare[$iKey][$sField])){
							echo \Service\Utils::showDiff($mData, $aQueryDataCompare[$iKey][$sField], $oKpi->INVERT_COLOR, $oKpi->DIFF_PERCENT);						
						}else{
							echo \Service\Utils::showDiff($mData, 0, $oKpi->INVERT_COLOR, $oKpi->DIFF_PERCENT);
						}
					}elseif(is_numeric($mData) && $oKpi->FORMAT_INTEGER){
						echo '<font size="2" style="font-size: 12px;">'.\Service\Utils::number($mData).'</font>';
					}else{
						echo '<font size="2" style="font-size: 12px;">'.$mData.'</font>';
					}
					?>
				</td>
			<?php endforeach;?>
			</tr>
		<?php endforeach;?>
		
	<?php else:?>
	
		<?php foreach ($aQueryData as $sField => $aData):?>
			<?php if($i==0):?>
				<tr>
					<th bgcolor="#CCCCCC">&nbsp;</th>
				<?php foreach ($aData as $sCol => $mData):?>
					<th bgcolor="#CCCCCC"><font size="2" style="font-size: 12px;"><?=$sCol;?></font></th>
				<?php endforeach;?>
				</tr>
			<?php endif;?>
			<?php $i++;?>
			<tr>
				<td bgcolor="#FFFFFF"><font size="2" style="font-size: 12px;"><?=$sField;?></font></td>
			<?php foreach ($aData as $sCol => $mData):?>
				<td bgcolor="#FFFFFF">
				<?php
				if(is_numeric($mData) && !empty($oKpi->QUERY_COMPARE)){
					if(isset($aQueryDataCompare[$sField][$sCol])){
						echo \Service\Utils::showDiff($mData, $aQueryDataCompare[$sField][$sCol], $oKpi->INVERT_COLOR, $oKpi->DIFF_PERCENT);						
					}else{
						echo \Service\Utils::showDiff($mData, 0, $oKpi->INVERT_COLOR, $oKpi->DIFF_PERCENT);
					}
				}elseif(is_numeric($mData) && $oKpi->FORMAT_INTEGER){
					echo '<font size="2" style="font-size: 12px;">'.\Service\Utils::number($mData).'</font>';
				}else{
					echo '<font size="2" style="font-size: 12px;">'.$mData.'</font>';
				}
				?>
				</td>
			<?php endforeach;?>
			</tr>
		<?php endforeach;?>
	
	<?php endif;?>
	</table>
	
<?php endif;?>