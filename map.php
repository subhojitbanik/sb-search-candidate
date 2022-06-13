<?php
//ini_set('display_errors', 'off');

function search_candidate_fn(){
?>

<div class="container p-0" style="overflow: hidden;">
    <h5 style="padding: 20px;background: #ccc;margin-bottom: 0px;font-weight: 600;">Search your address and select districts you belong to, this will help us to fetch candidates in your ballot</h5>
    <input id="pac-input" class="controls" type="text" placeholder="Search Box">

    <div class="district-progress" style="position: absolute; top:90%; left:5%;">
    <!-- <i class="fa-regular fa-circle"></i> <i class="fa-regular fa-circle-check"></i> -->
        <div id="cd-check" class="d-check"><i class="fa fa-circle-o"></i>Congressional District</div>
        <div id="sd-check" class="d-check"><i class="fa fa-circle-o"></i>Senate District</div>
        <div id="hd-check" class="d-check"><i class="fa fa-circle-o"></i>House District</div>
        <div id="cb-check" class="d-check"><i class="fa fa-circle-o"></i>Count Boundaries</div>
    </div>
    <div id="map"  style="height:500px; width:700px; margin-top:0px;" class="container"></div>
</div>
<?php } 
function show_result_fn($atts){
   
    $cd = $_GET['cd'];
    $sd = $_GET['sd'];
    $hd = $_GET['hd'];
    $cb = $_GET['cb'];
    if(empty($cd) && empty($sd) && empty($hd) && empty($cb)){
        search_candidate_fn();
    }else{
        if($atts['virtual-ballot']){
            virtual_ballot_fn();
        }else{
            show_ofc_cat_fn();
        }
        
    }
    
}
add_shortcode('search_candidate','show_result_fn');


