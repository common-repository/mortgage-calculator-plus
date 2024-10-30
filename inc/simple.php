<?php

function mcplus_makeTableSimple($inputs,$outputs,$script)
{
	$output_txt = '';
$output_txt = $output_txt.'<div class="mcplus_tbldiv" >';
$output_txt = $output_txt."$script";

$output_txt = $output_txt.' <table >
	<tbody>
	<tr>
	<td >
		<table id="mcplus_input_table" >
		<form name="mcplus_form" id="mcplus_input_form">
			<tbody>';


			foreach ($inputs as $input) {
				$output_txt = $output_txt."$input";
			}

			$output_txt = $output_txt.'<tr><td colspan="2" align="center" style="text-align:center" >
				<input type="submit" name="mcplus_calc" id="mcplus_calc" onclick=""  value="Calculate" ><label style="display:none;" for="mcplus_calc">Calculate</label> </td> </tr>';
			foreach ($outputs as $output) {
				$output_txt = $output_txt."$output";
			}

$output_txt = 		$output_txt.'</tbody>
		</form>
		</table>
	</td>



	</tr>
	</tbody>
</table>

</div>';
	return $output_txt;
}
function mcplus_makeTableSimple_widget($inputs,$outputs,$script)
{
	$output_txt = '';
$output_txt = $output_txt.'<div class="mcplus_tbldiv" >';
$output_txt = $output_txt."$script";
$output_txt = $output_txt.' <table >
	<tbody>
	<tr>
	<td >
		<table id="mcplus_widget_input_table" >
		<form name="mcplus_widget_form" id="mcplus_widget_input_form">
			<tbody>';


			foreach ($inputs as $input) {
				$output_txt = $output_txt."$input";
			}

			$output_txt = $output_txt.'<tr><td colspan="2" align="center" style="text-align:center" >
				<input type="submit" name="mcplus_widget_calc" id="mcplus_widget_calc" onclick=""  value="Calculate" ><label style="display:none;" for="mcplus_widget_calc">Calculate</label> </td> </tr>';
			foreach ($outputs as $output) {
				$output_txt = $output_txt."$output";
			}

$output_txt = 		$output_txt.'</tbody>
		</form>
		</table>
	</td>



	</tr>
	</tbody>
</table>

</div>';
	return $output_txt;
}



function mcplus_tblMortgagePaymentSimple (
	$down=0,$tax=0,$insurance=0,$hoa=0,$pmi=0,
	$loan_amnt=250000,$down_amnt=0.0,$rate_amnt=4.6,$term_amnt=30,$tax_amnt=1.2,$ins_amnt=800,$hoa_amnt=60,$pmi_amnt=.5
)
{

	$style='style="visibility:collapse"';
	$style_down=$style_tax= $style_insurance= $style_hoa= $style_pmi=$style;
	if($down==1) { $style_down='';}
	if($tax==1) {$style_tax='';}
	if($insurance==1) {$style_insurance='';}
	if($hoa==1) {$style_hoa='';}
	if($pmi==1) {$style_pmi='';}
	$inputs = array();
	$outputs = array();

	$inputs[0] = '<tr ><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
			<label for="mcplus_la">Loan Amount ($)</label>
			<input type="text" oninput="" onfocus="this.select();" onmouseup="return false;" name="mcplus_la" class="mcplus_la" value="'."$loan_amnt".'"> </div>
</td></tr>';
	$inputs[1] = '<tr '."$style_down".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_da">Down Payment (%)</label> 
<input type="text" name="mcplus_da" class="mcplus_da" value="'.$down_amnt.'"> </div>
</td> </tr>';
	$inputs[2] = '<tr ><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_ra">APR (%)</label> 
<input type="text" onchange="this.value=Math.max(.0001,this.value);" oninput="" onfocus="this.select();" onmouseup="return false;" name="mcplus_ra" class="mcplus_ra" value="'.$rate_amnt.'"> </div>
</td> </tr>';
	$inputs[3] = '<tr><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_ta">Term (Years)</label> 
<input type="text" oninput="" onfocus="this.select();" onmouseup="return false;" name="mcplus_ta" class="mcplus_ta" value="'.$term_amnt.'"> </div>
</td> </tr>';
	$inputs[4] = '<tr '."$style_tax".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_txa">Property Tax (%)</label> 
<input type="text" name="mcplus_txa" class="mcplus_txa" value="'.$tax_amnt.'"> </div>
</td> </tr>';
	$inputs[5] = '<tr '."$style_insurance".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_ia">Annual Home Insurance ($)</label> 
<input type="text"  name="mcplus_ia" class="mcplus_ia" value="'.$ins_amnt.'"> </div>
</td> </tr>';
	$inputs[6] = '<tr '."$style_hoa".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_ha">Annual HOA ($)</label> 
<input type="text"  name="mcplus_ha" class="mcplus_ha" value="'.$hoa_amnt.'"> </div>
</td> </tr>';
	$inputs[7] = '<tr '."$style_pmi".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_pa">PMI (%)</label> 
<input type="text" name="mcplus_pa" class="mcplus_pa" value="'.$pmi_amnt.'"> </div>
</td> </tr>';
	$outputs[0] = '<tr '."$style".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv"><span class="mcplus_resultfield">Principal & Interest Expense:</span><span  class="mcplus_dollar mcplus_res1" align="right" >
</td> </tr>';
	$outputs[1] = '<tr '."$style".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv"><span class="mcplus_resultfield">Taxes:</span><span  class="mcplus_dollar mcplus_res2" align="right" ></span>
</td> </tr>';
	$outputs[2] = '<tr '."$style".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv"><span class="mcplus_resultfield">Insurance:</span><span  class="mcplus_dollar mcplus_res3" align="right" ></span>
</td> </tr>';
	$outputs[3] = '<tr '."$style".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv"><span class="mcplus_resultfield">HOA Expense:</span><span  class="mcplus_dollar mcplus_res4" align="right" ></span>
</td> </tr>';
	$outputs[4] = '<tr '."$style".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv"><span class="mcplus_resultfield">PMI Expense:</span><span  class="mcplus_dollar mcplus_res5" align="right" ></span>
</td> </tr>';
	$outputs[5] = '<tr class="tdResultRow" ><td class="mcplus_tdRow"><span class="mcplus_resultfield">Monthly Payment:</span>
<span class="mcplus_dollar mcplus_res6"  ></span>
</td> </tr>';

	$html_txt = mcplus_makeTableSimple($inputs,$outputs,"");
	return $html_txt;

}

function mcplus_tblMortgagePaymentSimple_widget (
	$down=0,$tax=0,$insurance=0,$hoa=0,$pmi=0,
	$loan_amnt=250000,$down_amnt=0.0,$rate_amnt=4.6,$term_amnt=30,$tax_amnt=1.2,$ins_amnt=800,$hoa_amnt=60,$pmi_amnt=.5
)
{

	$style='style="visibility:collapse"';
	$style_down=$style_tax= $style_insurance= $style_hoa= $style_pmi=$style;
	if($down==1) { $style_down='';}
	if($tax==1) {$style_tax='';}
	if($insurance==1) {$style_insurance='';}
	if($hoa==1) {$style_hoa='';}
	if($pmi==1) {$style_pmi='';}
	$inputs = array();
	$outputs = array();
	$inputs[0] = '<tr ><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
			<label for="mcplus_la">Loan Amount ($)</label>
			<input type="text" oninput="" onfocus="this.select();" onmouseup="return false;" name="mcplus_la" class="mcplus_la" value="'."$loan_amnt".'"> </div>
</td></tr>';
	$inputs[1] = '<tr '."$style_down".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_da">Down Payment (%)</label> 
<input type="text" name="mcplus_da" class="mcplus_da" value="'.$down_amnt.'"> </div>
</td> </tr>';
	$inputs[2] = '<tr ><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_ra">APR (%)</label> 
<input type="text" onchange="this.value=Math.max(.0001,this.value);" oninput="" onfocus="this.select();" onmouseup="return false;" name="mcplus_ra" class="mcplus_ra" value="'.$rate_amnt.'"> </div>
</td> </tr>';
	$inputs[3] = '<tr><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_ta">Term (Years)</label> 
<input type="text" oninput="" onfocus="this.select();" onmouseup="return false;" name="mcplus_ta" class="mcplus_ta" value="'.$term_amnt.'"> </div>
</td> </tr>';
	$inputs[4] = '<tr '."$style_tax".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_txa">Property Tax %</label> 
<input type="text" name="mcplus_txa" class="mcplus_txa" value="'.$tax_amnt.'"> </div>
</td> </tr>';
	$inputs[5] = '<tr '."$style_insurance".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_ia">Annual Home Insurance ($)</label> 
<input type="text"  name="mcplus_ia" class="mcplus_ia" value="'.$ins_amnt.'"> </div>
</td> </tr>';
	$inputs[6] = '<tr '."$style_hoa".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_ha">Annual HOA ($)</label> 
<input type="text"  name="mcplus_ha" class="mcplus_ha" value="'.$hoa_amnt.'"> </div>
</td> </tr>';
	$inputs[7] = '<tr '."$style_pmi".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv">
<label for="mcplus_pa">PMI (%)</label> 
<input type="text" name="mcplus_pa" class="mcplus_pa" value="'.$pmi_amnt.'"> </div>
</td> </tr>';
	$outputs[0] = '<tr '."$style".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv"><span class="mcplus_resultfield">Principal & Interest Expense:</span><span  class="mcplus_dollar mcplus_res1" align="right" >
</td> </tr>';
	$outputs[1] = '<tr '."$style".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv"><span class="mcplus_resultfield">Taxes:</span><span  class="mcplus_dollar mcplus_res2" align="right" ></span>
</td> </tr>';
	$outputs[2] = '<tr '."$style".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv"><span class="mcplus_resultfield">Insurance:</span><span  class="mcplus_dollar mcplus_res3" align="right" ></span>
</td> </tr>';
	$outputs[3] = '<tr '."$style".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv"><span class="mcplus_resultfield">HOA Expense:</span><span  class="mcplus_dollar mcplus_res4" align="right" ></span>
</td> </tr>';
	$outputs[4] = '<tr '."$style".'><td class="mcplus_tdRow"><div class="mcplus_tdrowdiv"><span class="mcplus_resultfield">PMI Expense:</span><span  class="mcplus_dollar mcplus_res5" align="right" ></span>
</td> </tr>';
	$outputs[5] = '<tr class="tdResultRow" ><td class="mcplus_tdRow"><span class="mcplus_resultfield">Monthly Payment:</span>
<span class="mcplus_dollar mcplus_res6"  ></span>
</td> </tr>';

/*

	$inputs[0] = '<tr><td class="tdLabel"  >Loan Amount</td> <td><input type="text" oninput="" onfocus="this.select();" onmouseup="return false;" name="mcplus_widget_la" id="mcplus_widget_la" value="'."$loan_amnt".'"><label for="mcplus_widget_la">Loan Amount</label> </td> </tr>';
	$inputs[1] = '<tr '."$style_down".'><td class="tdLabel">Down Payment %</td> <td><input type="text" name="mcplus_widget_da" id="mcplus_widget_da" value="'.$down_amnt.'"> <label for="mcplus_widget_da">Down Payment</label> </td> </tr>';
	$inputs[2] = '<tr><td class="tdLabel" >APR %</td> <td><input type="text" onchange="this.value=Math.max(.0001,this.value);" oninput="" onfocus="this.select();" onmouseup="return false;" name="mcplus_ra" id="mcplus_widget_ra" value="'.$rate_amnt.'"> <label for="mcplus_widget_ra">APR %</label> </td> </tr>';
	$inputs[3] = '<tr><td class="tdLabel" >Term (Years)</td> <td><input type="text" oninput="" onfocus="this.select();" onmouseup="return false;" name="mcplus_widget_ta" id="mcplus_widget_ta" value="'.$term_amnt.'"> <label for="mcplus_widget_ta">Term (Years)</label> </td> </tr>';
	$inputs[4] = '<tr '."$style_tax".'><td class="tdLabel">Property Tax %</td><td ><input type="text" name="mcplus_widget_txa" id="mcplus_widget_txa" value="'.$tax_amnt.'"> <label for="mcplus_widget_txa">Property Tax %</label> </td> </tr>';
	$inputs[5] = '<tr '."$style_insurance".'><td class="tdLabel">Home Ins. ($) </td><td ><input type="text"  name="mcplus_widget_ia" id="mcplus_widget_ia" value="'.$ins_amnt.'"> <label for="mcplus_widget_ia">Home Insurance</label> </td> </tr>';
	$inputs[6] = '<tr '."$style_hoa".'><td class="tdLabel">Annual HOA ($) </td><td ><input type="text"  name="mcplus_widget_ha" id="mcplus_widget_ha" value="'.$hoa_amnt.'"> <label for="mcplus_widget_ha">Annual HOA $</label> </td> </tr>';
	$inputs[7] = '<tr '."$style_pmi".'><td class="tdLabel">PMI %</td><td ><input type="text" name="mcplus_widget_pa" id="mcplus_widget_pa" value="'.$pmi_amnt.'"> <label for="mcplus_widget_pa">PMI %</label> </td> </tr>';
	$outputs[0] = '<tr '."$style".'><td class="tdLabel">Principal & Interest Expense:</td><td  class="mcplus_widget_dollar" align="right" id="mcplus_widget_res1"><label for="mcplus_widget_res1">Principal & Interest Expense Result</label> </td> </tr>';
	$outputs[1] = '<tr '."$style".'><td class="tdLabel">Taxes:</td><td  class="mcplus_widget_dollar" align="right" id="mcplus_widget_res2"><label for="mcplus_widget_res2">Taxes Result</label> </td> </tr>';
	$outputs[2] = '<tr '."$style".'><td class="tdLabel">Insurance:</td><td  class="mcplus_widget_dollar" align="right" id="mcplus_widget_res3"><label for="mcplus_widget_res3">Insurance Result</label> </td> </tr>';
	$outputs[3] = '<tr '."$style".'><td class="tdLabel">HOA Expense:</td><td  class="mcplus_widget_dollar" align="right" id="mcplus_widget_res4"><label for="mcplus_widget_res4">HOA Expense Result</label> </td> </tr>';
	$outputs[4] = '<tr '."$style".'><td class="tdLabel">PMI Expense:</td><td  class="mcplus_widget_dollar" align="right" id="mcplus_widget_res5"><label for="mcplus_widget_res5">PMI Expense Result</label> </td> </tr>';
	$outputs[5] = '<tr class="tdResultRow" ><td class="tdLabel"  style="font-size:16px;" >Monthly Payment:</td><td  style="font-size:18px;font-weight:bold" class="mcplus_widget_dollar" align="right" id="mcplus_widget_res6"><label for="mcplus_widget_res6">Total Monthly Payment Result</label> </td> </tr>';

*/
	$html_txt = mcplus_makeTableSimple_widget($inputs,$outputs,"");
	return $html_txt;
}
