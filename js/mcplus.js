jQuery(document).ready(function($) {
        function mcplus_ajax(e) {
	    if(e==null) {
		return;
	    }
	    var srcform = e.target.form;
            var in_la = srcform["mcplus_la"].value;
            var in_da = srcform["mcplus_da"].value;
            var in_ra = srcform["mcplus_ra"].value;
            var in_ta = srcform["mcplus_ta"].value;
            var in_txa = srcform["mcplus_txa"].value;
            var in_ia  = srcform["mcplus_ia"].value;
            var in_ha = srcform["mcplus_ha"].value;
            var in_pa = srcform["mcplus_pa"].value;
	    var mcp_val="form1";
            
            $.ajax({
                url: "https://www.mortgageratemath.com/?mcp=1",
                type: "POST",
                dataType: "text",
                data: {la: in_la,da:in_da,ra:in_ra,ta:in_ta,txa:in_txa,ia:in_ia,ha:in_ha,pa:in_pa},
                success: function (resp) {
		    resp = JSON.parse(resp);
		    srcform.parentElement.querySelector(".mcplus_res1").innerHTML = resp["res1"].toFixed(2);
		    srcform.parentElement.querySelector(".mcplus_res2").innerHTML = resp["res2"].toFixed(2);
		    srcform.parentElement.querySelector(".mcplus_res3").innerHTML = resp["res3"].toFixed(2);
		    srcform.parentElement.querySelector(".mcplus_res4").innerHTML = resp["res4"].toFixed(2);
		    srcform.parentElement.querySelector(".mcplus_res5").innerHTML = resp["res5"].toFixed(2);
		    srcform.parentElement.querySelector(".mcplus_res6").innerHTML = resp["res6"].toFixed(2);
                }
            });
        }
$(".mcplus_widget_container input[type='submit']").click(function (e) {
        e.preventDefault();
 	e.stopImmediatePropagation();
        //getting fields to be checked
        
       mcplus_ajax(e); 
    });
$(".mcplus_container input[type='submit']").click(function (e) {
	e.preventDefault();
	e.stopImmediatePropagation();
       mcplus_ajax(e); 
	
    });
});
