<?php
require_once(SB_SEARCH_CANDIDATE_PLUGIN_DIR . 'vendor/autoload.php');

$vendr = new Google_Client();

$vendr->setApplicationName('app');

$vendr->setScopes([\Google_Service_Sheets::SPREADSHEETS]);

$vendr->setAuthConfig(get_template_directory() . '/credentials.json');

$vendr->setAccessType('offline');

$service = new Google_Service_Sheets($vendr);

$spreadsheetId = '1Bn5gYVDGIVqelsCMaDWpHLHLoyeMHTtk5ByjvNVygzg';

$range = 'All Candidates';

$response = $service->spreadsheets_values->get($spreadsheetId, $range);

$lists_val = $response->getValues();
function search_data($array, $key, $value)
{
    $results = array();
    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, search_data($subarray, $key, $value));
        }
    }
    return $results;
}

 $data = search_data($lists_val, 26, 'active');
 
function show_ofc_cat_fn()
{
    global $data;   

    $uss_input = 'US Senator';
    $sos_input = 'Secretary of State';
    $uss = search_data($data, 13, $uss_input);
    $sos = search_data($data, 13, $sos_input);
    // echo '<pre>';
    // print_r($sos);
    // echo '</pre>';
    
    foreach ($uss as $us_val) {
        $arr_uss[] = $us_val[13];
    }
    foreach ($sos as $sos_val) {
        $arr_sos[] = $sos_val[13];
    }
    if (!empty($_GET['cd'])) {
        $cd_input = $_GET['cd'];
    }
    $cd = search_data($data, 19, $cd_input);
    foreach ($cd as $val) {
        $arrcd[] = $val[13];
    }
    //$arr = array_values(array_unique($arr));
    if (!empty($_GET['sd'])) {
        $sd_input = $_GET['sd'];
    }
    $sd = search_data($data, 20, $sd_input);
    foreach ($sd as $val) {
        $arrsd[] = $val[13];
    }
    if (!empty($_GET['hd'])) {
        $hd_input = $_GET['hd'];
    }
    $hd = search_data($data, 21, $hd_input);
    foreach ($hd as $val) {
        $arrhd[] = $val[13];
    }
    if (!empty($_GET['cb'])) {
        $cb_input = $_GET['cb'];
    }
    $cb = search_data($data, 22, $cb_input);
    foreach ($cb as $val) {
        $arrcb[] = $val[13];
    }
    $cb_pa = search_data($cb, 13, 'Prosecuting Attorney');
    $cb_ca = search_data($cb, 13, 'County Assessor');
    $cb_cs = search_data($cb, 13, 'County Sheriff');
    $cb_cau = search_data($cb, 13, 'County Auditor');
    $cb_cc = search_data($cb, 13, 'County Commissioner');
    $cb_csur = search_data($cb, 13, 'County Surveyor');
    $cb_ct = search_data($cb, 13, 'County Treasurer');
    $cb_cco = search_data($cb, 13, 'County Coroner');
    $cb_cr = search_data($cb, 13, 'County Recorder');
    $cb_jcc = search_data($cb, 13, 'Judge, Circuit Court');
    $cb_jsmc = search_data($cb, 13, 'Judge, Small Claims Court');
    $cb_jsc = search_data($cb, 13, 'Judge, Superior Court');
    $arr[] = array_merge((array)$arr_uss,
        (array)$arr_sos,
        (array)$arrcd,
        (array)$arrsd,
        (array)$arrhd,
        (array)$arrcb
    );
    
    ?>
    <div class="container">
        <div class="row my-3">
            <div class="col-md-6 col-sm-12">
                <form action="" method="post" class="my-3">
                    <label for="ofc_cat">Select Office Category</label>
                    <select name="offc_category" id="ofc_cat" class="form-control">
                        <option value="show_hide">Show All</option>
                        <?php
                        foreach ($arr as $val) {
                            for ($i = 0; $i < sizeof($val); $i++) {
                                $sec_id = str_replace(' ', '_', $val[$i]);
                        ?>
                                <option value="<?php echo $sec_id; ?>"><?php echo $val[$i]; ?></option>
                        <?php }
                        } ?>
                    </select>
                </form>
            </div>
            <div class="col-md-6 col-sm-12"></div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <!-- US Senator starts -->
                <?php if (in_array('US Senator', (array)$arr_uss)) { ?>
                    <div class="row show_hide <?php print(str_replace(' ', '_', $arr_uss[0])); ?>">
                        <?php  echo '<h2 style="text-align:left;"> ' . $arr_uss[0] . ' </h2> <div class="container"><hr></div>';
                        foreach ($uss as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php }?>
                <!-- US Senator ends -->
                <!-- Secretary of State starts -->
                <?php if (in_array('Secretary of State', (array)$arr_sos)) { ?>
                    <div class="row show_hide <?php print(str_replace(' ', '_', $arr_sos[0])); ?>">
                        <?php  echo '<h2 style="text-align:left;"> ' . $arr_sos[0] . ' </h2> <div class="container"><hr></div>';
                        foreach ($sos as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <!-- Secretary of State ends -->
                <!-- US Representative starts -->
                <div class="row show_hide <?php print(str_replace(' ', '_', $arrcd[0])); ?>">
                    <?php if (isset($_GET["cd"])) {
                        echo '<h2 style="text-align:left;"> ' . $arrcd[0] . ' </h2> <div class="container"><hr></div>';
                        foreach ($cd as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
                <!-- US Representative ends -->
                <!-- State Senator starts -->
                <?php if (in_array('State Senator', (array)$arrsd)) { ?>
                <div class="row show_hide <?php print(str_replace(' ', '_', $arrsd[0])); ?>">
                    <?php if (isset($_GET["sd"])) {
                        echo '<h2 style="text-align:left;"> ' . $arrsd[0] . '</h2> <div class="container"><hr></div>';
                        foreach ($sd as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <div class="container">
                                        <hr class="hrline">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
                <?php }?>
                <!-- State Senator ends -->
                <!-- house district starts -->
                <div class="row show_hide <?php print(str_replace(' ', '_', $arrhd[0])); ?>">
                    <?php if (isset($_GET["hd"])) {
                        echo '<h2 style="text-align:left;"> ' . $arrhd[0] . ' </h2> <div class="container"><hr></div>';
                        foreach ($hd as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
                <!-- house district ends -->
                <?php if (in_array('Prosecuting Attorney', (array)$arrcb)) { ?>
                    <div class="row show_hide <?php print(str_replace(' ', '_', $arrcb[0])); ?>">
                        <?php echo '<h2 style="text-align:left;"> Prosecuting Attorney </h2> <div class="container"><hr></div>';?>
                        <div class="row pros_attorn">
                            <?php foreach ($cb_pa as $fd) { ?>
                                <div class="col-md-4">
                                    <div class="card mb-3">
                                        <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                        <div class="card-body">
                                            <div class="circular--portrait">
                                                <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                            </div>
                                            <h3 class="card-title"><?php if ($fd[15]) {
                                                                        echo $fd[15];
                                                                    } ?></h3>
                                            <?php
                                            echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                            <hr class="hrline">
                                            <div class="row">
                                                <div class="col-sm">
                                                    <p class="card-text">Ballot Order </p>
                                                    <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                    <p class="card-text">Office</p>
                                                    <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                                ?></h4> -->
                                                    <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                                </div>
                                                <div class="col-sm">
                                                    <p class="card-text">Terms End</p>
                                                    <h4 class="card-title">2022</h4>
                                                    <p class="card-text">Last Elected</p>
                                                    <h4 class="card-title">2022</h4>
                                                </div>
                                            </div>
                                            <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries prosecuting Attorney ends -->
                <!-- County Boundaries County Assessor starts -->
                <?php if (in_array('County Assessor', (array)$arrcb)) { ?>
                    <div class="row show_hide County_Assessor my-3">
                        <?php echo '<h2 style="text-align:left;"> County Assessor </h2> <div class="container"><hr></div>';?>
                        <div class="row cb_ca">
                        <?php foreach ($cb_ca as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries County Assessor ends -->
                <!-- County Boundaries County Sheriff starts -->
                <?php if (in_array('County Sheriff', (array)$arrcb)) { ?>
                    <div class="row show_hide County_Sheriff my-3">
                        <?php echo '<h2 style="text-align:left;"> County Sheriff </h2> <div class="container"><hr></div>';?>
                        <div class="row cb_cs">
                        <?php foreach ($cb_cs as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries County Sheriff ends -->
                <!-- County Boundaries County Auditor starts -->
                <?php if (in_array('County Auditor', (array)$arrcb)) { ?>
                    <div class="row show_hide County_Auditor my-3">
                        <?php echo '<h2 style="text-align:left;"> County Auditor </h2> <div class="container"><hr></div>';?>
                        <div class="row cb_cau">
                        <?php foreach ($cb_cau as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries County Auditor ends -->
                <!-- County Boundaries County Commissioner starts -->
                <?php if (in_array('County Commissioner', (array)$arrcb)) { ?>
                    <div class="row show_hide County_Commissioner my-3">
                        <?php echo '<h2 style="text-align:left;"> County Commissioner </h2> <div class="container"><hr></div>';?>
                        <div class="row cb_cc">
                        <?php foreach ($cb_cc as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries County Commissioner ends -->
                <!-- County Boundaries County Surveyor starts -->
                <?php if (in_array('County Surveyor', (array)$arrcb)) { ?>
                    <div class="row show_hide County_Surveyor my-3">
                        <?php echo '<h2 style="text-align:left;"> County Surveyor </h2> <div class="container"><hr></div>';?>
                        <div class="row cb_csur">
                        <?php foreach ($cb_csur as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries County Surveyor ends -->
                <!-- County Boundaries County Treasurer starts -->
                <?php if (in_array('County Treasurer', (array)$arrcb)) { ?>
                    <div class="row show_hide County_Treasurer my-3">
                        <?php echo '<h2 style="text-align:left;"> County Treasurer </h2> <div class="container"><hr></div>'?>;
                        <div class="row cb_ct">
                        <?php foreach ($cb_ct as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries County Treasurer ends -->
                <!-- County Boundaries County Coroner starts -->
                <?php if (in_array('County Coroner', (array)$arrcb)) { ?>
                    <div class="row show_hide County_Coroner my-3">
                        <?php echo '<h2 style="text-align:left;"> County Coroner </h2> <div class="container"><hr></div>';?>
                        <div class="row cb_cco">
                        <?php foreach ($cb_cco as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries County Coroner ends -->
                <!-- County Boundaries County Recorder starts -->
                <?php if (in_array('County Recorder', (array)$arrcb)) { ?>
                    <div class="row show_hide County_Recorder my-3">
                        <?php echo '<h2 style="text-align:left;"> County Recorder </h2> <div class="container"><hr></div>';?>
                        <div class="row cb_cr">
                        <?php foreach ($cb_cr as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries County Recorder ends -->
                <!-- County Boundaries Judge, Circuit Court starts -->
                <?php if (in_array('Judge, Circuit Court', (array)$arrcb)) { ?>
                    <div class="row show_hide Judge__Circuit_Court my-3">
                        <?php echo '<h2 style="text-align:left;"> Judge, Circuit Court </h2> <hr>';?>
                        <div class="row cb_jcc">
                        <?php foreach ($cb_jcc as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries Judge, Circuit Court ends -->
                <!-- County Boundaries Judge, Small Claims Court starts -->
                <?php if (in_array('Judge, Small Claims Court', (array)$arrcb)) { ?>
                    <div class="row show_hide Judge__Small_Claims_Court">
                        <?php echo '<h2 style="text-align:left;"> Judge, Small Claims Court </h2> <hr>';?>
                        <div class="cb_jsmc row">
                        <?php foreach ($cb_jsmc as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries Judge, Small Claims Court ends -->
                <!-- County Boundaries Judge, Superior Court starts -->
                <?php if (in_array('Judge, Superior Court', (array)$arrcb)) { ?>
                    <div class="row show_hide Judge__Superior_Court">
                        <?php echo '<h2 style="text-align:left;"> Judge, Superior Court </h2> <hr>';?>
                        <div class="cb_jsc row">
                        <?php foreach ($cb_jsc as $fd) { ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-header1 <?php echo $fd[16]; ?>"><?php echo $fd[16]; ?></div>
                                    <div class="card-body">
                                        <div class="circular--portrait">
                                            <img src="https://dev.indianacitizen.org/wp-content/uploads/2022/04/not_avail.png" />
                                        </div>
                                        <h3 class="card-title"><?php if ($fd[15]) {
                                                                    echo $fd[15];
                                                                } ?></h3>
                                        <?php
                                        echo $pd_follow_sc = do_shortcode('[pd_follow content_id="' . $fd[23] . '" content_type="1" user_id="6"]'); ?>
                                        <hr class="hrline">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="card-text">Ballot Order </p>
                                                <h4 class="card-title"><?php echo $fd[14]; ?></h4>
                                                <p class="card-text">Office</p>
                                                <!-- <h4 class="card-title">candidateId : <?php // echo $fd['0']; 
                                                                                            ?></h4> -->
                                                <p class="card-title1"> <?php echo $fd['4']; ?></p>
                                            </div>
                                            <div class="col-sm">
                                                <p class="card-text">Terms End</p>
                                                <h4 class="card-title">2022</h4>
                                                <p class="card-text">Last Elected</p>
                                                <h4 class="card-title">2022</h4>
                                            </div>
                                        </div>
                                        <a class=" btn btn-warning" href="/my-representative/candidate-summary/?can_id=<?php echo $fd['23']; ?>">Detail Bio</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!-- County Boundaries Judge, Superior Court ends -->

            </div>
        </section>
    </div>

<?php }                                  
add_shortcode( 'uss', 'show_ofc_cat_fn' );