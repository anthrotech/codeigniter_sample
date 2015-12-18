    	<?php foreach($results->result_array() as $entry):?>
      			<table class="tableCaption">
					<tr>
						<td class="tableCaption" colspan="2">Social History - (Password Protection - <?=$perm?>)</td>
					</tr>
				</table>    	
				<table class="dataEntry">
					<tr>
						<td colspan="5" class="tableSubCaption">Tobacco Use</td>
					</tr>
					<tr>
						<td colspan="5" class="tableSubCaption">&nbsp;</td>
					</tr>						
					<tr>
						<td></td>
						<td class="label">Cigarettes</td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdCigarettsUse" VALUE="0" <?php if ($entry['CigarettsUse'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdCigarettsUse" VALUE="1"<?php if ($entry['CigarettsUse'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>
						<td class="field"><?php echo $this->utils->age('CigarettsPacksPerDayID','selCigarettsPacksPerDay',$entry['CigarettsPacksPerDay']);?><br/>packs per day</td>
						<td class="field"><?php echo $this->utils->age('CigarettsYearsUsedID','selCigarettsYearsUsed',$entry['CigarettsYearsUsed']);?> years</td>
					</tr>
					<tr>
						<td></td>
						<td class="label">Cigars</td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdCigarsUse" VALUE="0" <?php if ($entry['CigarsUse'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdCigarsUse" VALUE="1" <?php if ($entry['CigarsUse'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>
						<td class="field"><?php echo $this->utils->age('CigarsPerWeekID','selCigarsPerWeek',$entry['CigarsPerWeek']);?><br/>number per week</td>
						<td class="field"><?php echo $this->utils->age('CigarsYearsUsedID','selCigarsYearsUsed',$entry['CigarsYearsUsed']);?> years</td>
					</tr>
					<tr>
						<td></td>
						<td class="label">Chewing Tabacco</td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdChewingTobaccoUse" VALUE="0" <?php if ($entry['ChewingTobaccoUse'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdChewingTobaccoUse" VALUE="1" <?php if ($entry['ChewingTobaccoUse'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="2" class="label"></td>
						<td class="label">Have you quit?</td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdQuitTobacco" VALUE="0" <?php if($entry['QuitTobacco'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdQuitTobacco" VALUE="1" <?php if($entry['QuitTobacco'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>
						<td class="field">
							<?php echo preg_replace("/\r?\n/", "\\n", form_dropdown('selQTMM',$this->utils->month(),$entry['QTMM'],'id="QTMMID" style="width: 100px;"'));?> month
                        </td>
                        <td class="field">
                         	<?php echo $this->utils->years('QTYYYYID','selQTYYYY',$entry['QTYYYY']);?> year
                        </td>
					</tr>
					<tr>
						<td colspan="5" class="tableSubCaption">&nbsp;</td>
					</tr>					
					<tr>
						<td colspan="5" class="tableSubCaption">Alcohol Use</td>
					</tr>
					<tr>
						<td></td>
						<td class="label">Beer</td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdBeerUse" VALUE="0" <?php if($entry['BeerUse'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdBeerUse" VALUE="1" <?php if($entry['BeerUse'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>
						<td class="field"><?php echo $this->utils->age('BeerPerWeekID','selBeerPerWeek',$entry['BeerPerWeek']);?> per week</td>
					</tr>
					<tr>
						<td></td>
						<td class="label">Wine</td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdWineUse" VALUE="0" <?php if($entry['WineUse'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdWineUse" VALUE="1" <?php if($entry['WineUse'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>
						<td class="field"><?php echo $this->utils->age('WinePerWeekID','selWinePerWeek',$entry['WinePerWeek']);?> per week</td>
					</tr>
					<tr>
						<td></td>
						<td class="label">Liquor</td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdLiquorUse" VALUE="0" <?php if($entry['LiquorUse'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdLiquorUse" VALUE="1" <?php if($entry['LiquorUse'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>
						<td class="field"><?php echo $this->utils->age('LiquorPerWeekID','selLiquorPerWeek',$entry['LiquorPerWeek']);?> per week</td>
					</tr>
					<tr>
						<td colspan="2" class="label"></td>
						<td class="label">Have you quit?</td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdQuitAlcohol" VALUE="0" <?php if($entry['QuitAlcohol'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdQuitAlcohol" VALUE="1" <?php if($entry['QuitAlcohol'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>
						<td class="field">
						 	<?php echo preg_replace("/\r?\n/", "\\n", form_dropdown('selQAMM',$this->utils->month(),$entry['QAMM'],'id="QAMMID" style="width: 100px;"'));?> month				
						</td>
						<td class="field">
							<?php echo $this->utils->years('QAYYYYID','selQAYYYY',$entry['QAYYYY']);?> year
						</td>
					</tr> 		
					<tr>
						<td colspan="5" class="tableSubCaption">&nbsp;</td>
					</tr>  								
					<tr>
						<td colspan="5" class="tableSubCaption">Recreational Drug Use</td>
					</tr>  
					<tr>
						<td colspan="5" class="tableSubCaption">&nbsp;</td>
					</tr>					
					<tr>
						<td></td>  
						<td class="label">IV Drugs </td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdDrugIV" VALUE="0" <?php if($entry['DrugIV'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdDrugIV" VALUE="1" <?php if($entry['DrugIV'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>
					</tr>
					<tr>
						<td></td>
						<td class="label">Marijuana </td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdDrugMarijuana" VALUE="0" <?php if($entry['DrugMarijuana'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdDrugMarijuana" VALUE="1" <?php if($entry['DrugMarijuana'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>	
					</tr>
					<tr>
						<td></td>
						<td class="label">Cocaine </td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdDrugCocain" VALUE="0" <?php if($entry['DrugCocain'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdDrugCocain" VALUE="1" <?php if($entry['DrugCocain'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>	
					</tr>
					<tr>
						<td></td>
						<td class="label">Other </td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdDrugOther" VALUE="0" <?php if($entry['DrugOther'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdDrugOther" VALUE="1" <?php if($entry['DrugOther'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>
						<td class="label">Other - Please write in</td>
						<td class="field">
							<input type="text" name="txtDrugOtherDesc" size="15" maxlength="15" value='<?=$entry['DrugOtherDesc']?>'>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="label"></td>
						<td class="label">Have you quit?</td>
						<td class="field">
							<LABEL><INPUT TYPE="radio" NAME="RdQuitDrug" VALUE="0" <?php if($entry['QuitDrug'] == "0") echo 'CHECKED';?>> No</LABEL>
							<LABEL><INPUT TYPE="radio" NAME="RdQuitDrug" VALUE="1" <?php if($entry['QuitDrug'] == "1") echo 'CHECKED';?>> Yes</LABEL>
						</td>
						<td class="field">
							 <?php echo preg_replace("/\r?\n/", "\\n", form_dropdown('selQDMM',$this->utils->month(),$entry['QDMM'],'id="QDMMID" style="width: 100px;"'));?>  month
						</td>
						<td class="field">
							<?php echo $this->utils->years('QDYYYYID','selQDYYYY',$entry['QDYYYY']);?> year
						</td>
					</tr>
				</table>
				<table class="dataEntry">
					<tr>
						<td class="submit">
							<div class="buttonwrapper">
								<a class="squarebutton" onclick="return checkSocialHistory()" style="margin-left: 6px"><span>Save</span></a>
							</div>
						</td>	
					</tr>
				</table>			    	
    	<?php endforeach;?>     
                
<?php 
/* End of file socialhistory.php */
/* Location: ./system/application/views/forms/socialhistory.php */
?>       